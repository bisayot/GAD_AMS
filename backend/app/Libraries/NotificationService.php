<?php

namespace App\Libraries;

use App\Models\NotificationModel;
use App\Models\UserModel;

class NotificationService
{
    /**
     * Send a notification to a user, and also dispatch an email.
     * 
     * @param int $userId Target user ID
     * @param string $title Notification title
     * @param string $message Notification detailed message
     * @param string|null $link Optional link when clicking notification
     * @param string|null $type Optional type (e.g. 'success', 'warning', 'info')
     * @return bool
     */
    public static function resolveLink($link, $role)
    {
        if (empty($link)) return $link;

        if (in_array($role, ['admin', 'gad_staff'])) {
            $prefix = '/admin';
        } elseif (in_array($role, ['college', 'twg', 'non-twg'])) {
            $prefix = '/college';
        } else {
            $prefix = '/' . $role;
        }

        $isCollegeRole = in_array($role, ['college', 'twg', 'non-twg']);

        if (strpos($link, 'activity-designs') !== false || strpos($link, 'ad-list') !== false) {
            return $isCollegeRole ? "$prefix/submitted-list" : "$prefix/ad-list";
        }
        if (strpos($link, 'accomplishment-reports') !== false || strpos($link, 'ar-list') !== false) {
            return $isCollegeRole ? "$prefix/submitted-list" : "$prefix/ar-list";
        }
        if (strpos($link, 'messages') !== false) {
            return "$prefix/messages";
        }
        if (strpos($link, 'inquiries') !== false || strpos($link, 'contact-inquiries') !== false) {
            return "$prefix/contact-inquiries";
        }

        return $link;
    }

    public static function send($userId, $title, $message, $link = null, $type = 'info')
    {
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if ($user && $link) {
            $link = self::resolveLink($link, $user['role']);
        }

        // 1. Save to database
        $notificationModel = new NotificationModel();
        $notificationModel->insert([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
            'type'    => $type,
            'is_read' => 0
        ]);

        // 2. Send email
        if ($user && !empty($user['email'])) {
            self::sendEmail($user['email'], $user['first_name'], $title, $message, $link);
        }

        return true;
    }

    /**
     * Internal method to send email via CI4 Email service
     */
    private static function sendEmail($to, $name, $title, $message, $link)
    {
        // Build simple HTML layout
        $htmlMessage = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee;'>
            <h2 style='color: #990dd1;'>GAD AMS Notification</h2>
            <p>Hi " . htmlspecialchars($name ?? 'User') . ",</p>
            <p><strong>" . htmlspecialchars($title) . "</strong></p>
            <p>" . nl2br(htmlspecialchars($message)) . "</p>";
        
        if (!empty($link)) {
            $frontendUrl = env('FRONTEND_URL') ?: getenv('FRONTEND_URL') ?: 'http://localhost:5173';
            $fullLink = rtrim($frontendUrl, '/') . '/' . ltrim($link, '/');
            $htmlMessage .= "
            <div style='margin-top: 20px;'>
                <a href='" . htmlspecialchars($fullLink) . "' style='display: inline-block; padding: 10px 20px; background-color: #990dd1; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;'>View Details</a>
            </div>";
        }

        $htmlMessage .= "
            <br><br>
            <hr style='border: 0; border-top: 1px solid #eee;'>
            <p style='font-size: 12px; color: #888;'>This is an automated message from the GAD AMS System. Please do not reply.</p>
        </div>";

        // Check if we are in production and have a Brevo API key. Render blocks standard SMTP ports.
        $apiKey = env('BREVO_API_KEY') ?: getenv('BREVO_API_KEY');
        $isProduction = env('CI_ENVIRONMENT') === 'production' || getenv('CI_ENVIRONMENT') === 'production';

        if ($apiKey && $isProduction) {
            // Use Brevo HTTP API (Port 443) to bypass Render's SMTP block
            $fromEmail = env('FROM_EMAIL') ?: getenv('FROM_EMAIL') ?: 'gadims.bsu.bsit@gmail.com';
            $fromEmail = trim($fromEmail, '"\'');
            
            $ch = curl_init('https://api.brevo.com/v3/smtp/email');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'accept: application/json',
                'api-key: ' . trim($apiKey, '"\''),
                'content-type: application/json'
            ]);
            
            $payload = [
                'sender'      => ['name' => 'GAD AMS System', 'email' => $fromEmail],
                'to'          => [['email' => $to]],
                'subject'     => $title,
                'htmlContent' => $htmlMessage
            ];
            
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($httpCode < 200 || $httpCode >= 300) {
                log_message('error', 'Brevo API Notification Error: ' . $response . ' cURL: ' . $curlError);
            }
        } else {
            $email = \Config\Services::email();
            $email->setTo($to);
            $email->setSubject($title);
            $email->setMessage($htmlMessage);
            
            // We catch errors silently to not break the API if email fails
            try {
                $email->send();
            } catch (\Exception $e) {
                log_message('error', 'Failed to send notification email: ' . $e->getMessage());
            }
        }
    }

    /**
     * Send a notification to all admin users.
     * 
     * @param string $title Notification title
     * @param string $message Notification detailed message
     * @param string|null $link Optional link when clicking notification
     * @param string|null $type Optional type (e.g. 'success', 'warning', 'info')
     * @return void
     */
    public static function sendToAdmins($title, $message, $link = null, $type = 'info')
    {
        $db = \Config\Database::connect();
        $admins = $db->table('users')->where('role', 'admin')->orWhere('role', 'gad_staff')->get()->getResultArray();
        
        foreach ($admins as $admin) {
            self::send($admin['id'], $title, $message, $link, $type);
        }
    }
}

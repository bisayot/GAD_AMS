<?php

namespace App\Controllers;

use App\Models\ContactInquiryModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\NotificationService;

class ContactController extends BaseController
{
    use ResponseTrait;

    public function submit()
    {
        $rules = [
            'name'    => 'required|max_length[150]',
            'email'   => 'required|valid_email|max_length[150]',
            'subject' => 'required|max_length[150]',
            'message' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'name'    => $this->request->getVar('name'),
            'email'   => $this->request->getVar('email'),
            'subject' => $this->request->getVar('subject'),
            'message' => $this->request->getVar('message'),
            'status'  => 'new'
        ];

        $model = new ContactInquiryModel();
        
        if ($model->insert($data)) {
            // Send email using Brevo REST API to bypass Render's SMTP block
            $apiKey = env('BREVO_API_KEY') ?: env('SMTP_PASS') ?: env('email.SMTPPass') ?: '';
            $apiKey = trim($apiKey, '"\'');
            
            $senderEmail = env('FROM_EMAIL') ?: env('email.fromEmail') ?: 'gadims.bsu.bsit@gmail.com';
            $senderEmail = trim($senderEmail, '"\'');
            
            $adminEmail = 'gad.office@bsu.edu.ph'; // Replace with actual recipient if needed
            
            $htmlMessage = "
                <h2>New Contact Inquiry</h2>
                <p><strong>Name:</strong> {$data['name']}</p>
                <p><strong>Email:</strong> {$data['email']}</p>
                <p><strong>Subject:</strong> {$data['subject']}</p>
                <br/>
                <p><strong>Message:</strong></p>
                <p>" . nl2br(esc($data['message'])) . "</p>
            ";
            
            $ch = curl_init('https://api.brevo.com/v3/smtp/email');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'accept: application/json',
                'api-key: ' . $apiKey,
                'content-type: application/json'
            ]);
            
            $payload = [
                'sender'      => ['name' => $data['name'] . ' (via System)', 'email' => $senderEmail], // Using verified email as sender to avoid DMARC
                'replyTo'     => ['name' => $data['name'], 'email' => $data['email']],
                'to'          => [['email' => $adminEmail]],
                'subject'     => 'New Contact Inquiry: ' . $data['subject'],
                'htmlContent' => $htmlMessage
            ];
            
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $emailSent = ($httpCode >= 200 && $httpCode < 300);
            
            NotificationService::sendToAdmins('New Inquiry Received', 'A new contact inquiry from ' . $data['name'] . ' has been received.', '/admin/inquiries', 'info');
            
            return $this->respondCreated([
                'status'  => 201,
                'message' => 'Inquiry submitted successfully.',
                'email_sent' => $emailSent
            ]);
        }

        return $this->failServerError('Failed to save inquiry.');
    }

    public function index()
    {
        // For admin/staff to view inquiries
        $model = new ContactInquiryModel();
        
        $status = $this->request->getVar('status');
        if ($status) {
            $model->where('status', $status);
        }
        
        $inquiries = $model->orderBy('created_at', 'DESC')->findAll();
        
        return $this->respond(['inquiries' => $inquiries]);
    }

    public function markAsRead($id = null)
    {
        $model = new ContactInquiryModel();
        $inquiry = $model->find($id);
        
        if (!$inquiry) {
            return $this->failNotFound('Inquiry not found.');
        }
        
        $model->update($id, ['status' => 'read']);
        return $this->respond(['message' => 'Inquiry marked as read.']);
    }

    public function reply($id = null)
    {
        $model = new ContactInquiryModel();
        $inquiry = $model->find($id);
        
        if (!$inquiry) {
            return $this->failNotFound('Inquiry not found.');
        }

        $replyMessage = $this->request->getVar('reply_message');
        if (empty($replyMessage)) {
            return $this->failValidationErrors(['reply_message' => 'Reply message is required.']);
        }

        // Identify who is replying
        $actionUserId = $this->request->getHeaderLine('X-User-Id');
        $repliedByStatus = 'replied_staff';
        $replierName = 'GAD Staff';
        
        if ($actionUserId) {
            $userModel = new UserModel();
            $user = $userModel->find($actionUserId);
            if ($user) {
                if ($user['role'] === 'admin') {
                    $repliedByStatus = 'replied_director';
                    $replierName = 'GAD Director';
                }
            }
        }

        // Send Email using Brevo REST API to bypass Render's SMTP block
        $apiKey = getenv('BREVO_API_KEY') ?: env('BREVO_API_KEY') ?: getenv('SMTP_PASS') ?: env('SMTP_PASS') ?: env('email.SMTPPass') ?: '';
        $senderEmail = getenv('FROM_EMAIL') ?: env('FROM_EMAIL') ?: env('email.fromEmail') ?: 'gadims.bsu.bsit@gmail.com';
        
        $ticketNumber = 'INQ-' . str_pad($inquiry['id'], 5, '0', STR_PAD_LEFT);

        $htmlMessage = "
            <h2>Reply to Your Inquiry</h2>
            <p>Hello {$inquiry['name']},</p>
            <p>" . nl2br(esc($replyMessage)) . "</p>
            <br>
            <hr>
            <p style='font-size: 0.9em; color: #555;'>
                <em>Note: This is a one-way reply. If you wish to continue this conversation, you can create an account in our system or email the GAD Office directly at gad.office@bsu.edu.ph, making sure to include your ticket number <strong>{$ticketNumber}</strong> in your message.</em>
            </p>
            <hr>
            <p><strong>Your original message:</strong></p>
            <p><em>" . nl2br(esc($inquiry['message'])) . "</em></p>
        ";
        
        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . $apiKey,
            'content-type: application/json'
        ]);
        
        $payload = [
            'sender'      => ['name' => $replierName . ' - BSU GAD', 'email' => $senderEmail],
            'replyTo'     => ['name' => 'No Reply - BSU GAD', 'email' => 'noreply@bsu.edu.ph'],
            'to'          => [['email' => $inquiry['email']]],
            'subject'     => 'Re: ' . $inquiry['subject'] . ' [' . $ticketNumber . ']',
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
        
        if ($httpCode >= 200 && $httpCode < 300) {
            $model->update($id, ['status' => $repliedByStatus]);
            return $this->respond([
                'message' => 'Reply sent successfully.',
                'status' => $repliedByStatus
            ]);
        } else {
            log_message('error', 'Brevo API Error in ContactReply: ' . $response . ' cURL Error: ' . $curlError);
            return $this->failServerError('Failed to send email. You might have reached your daily limit.');
        }
    }

    public function delete($id = null)
    {
        $model = new ContactInquiryModel();
        $inquiry = $model->find($id);
        
        if (!$inquiry) {
            return $this->failNotFound('Inquiry not found.');
        }
        
        if ($model->delete($id)) {
            return $this->respondDeleted(['message' => 'Inquiry permanently deleted.']);
        }
        
        return $this->failServerError('Failed to delete inquiry.');
    }

    public function unreadCount()
    {
        $model = new ContactInquiryModel();
        $count = $model->where('status', 'new')->countAllResults();
        
        return $this->respond([
            'success' => true,
            'count' => $count
        ]);
    }
}

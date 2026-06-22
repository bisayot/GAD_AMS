<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    protected $format = 'json';

    public function login()
    {
        // Handle JSON or Form-data
        $data = $this->request->getJSON(true) ?: $this->request->getPost();

        $turnstileCheck = $this->verifyTurnstile($data['turnstile_token'] ?? '');
        if ($turnstileCheck['success'] !== true) {
            return $this->fail('Invalid security token: ' . ($turnstileCheck['error'] ?? 'Unknown Error') . 
                (isset($turnstileCheck['cloudflare_response']) ? ' | CF Details: ' . json_encode($turnstileCheck['cloudflare_response']) : ''));
        }

        $rules = [
            'identity' => 'required',
            'password' => 'required'
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $identity = trim($data['identity']);
        $password = $data['password'];

        log_message('error', "LOGIN ATTEMPT: Identity=[$identity]");

        $userModel = new UserModel();
        $user = $userModel->findByIdentity($identity);

        if (!$user) {
            return $this->failUnauthorized("User not found for identity: $identity");
        }

        if (!empty($user['deleted_at'])) {
            return $this->failUnauthorized("Your account has been suspended. Please contact the director.");
        }

        if (!password_verify($password, $user['password'])) {
            // Debug: Check if it's a legacy MD5 or something (unlikely but let's check)
            if (md5($password) === $user['password']) {
                 return $this->failUnauthorized('Legacy MD5 password detected. Please reset your password.');
            }
            return $this->failUnauthorized("Password verification failed for user: " . $user['username']);
        }

        log_message('error', "LOGIN SUCCESS: User [" . $user['username'] . "] authenticated.");

        // In a real app, you'd generate a JWT here. 
        // For this demo, we'll just return user info.
        $db = \Config\Database::connect();
        $userProfile = $db->table('user_profiles')->where('user_id', $user['id'])->get()->getRowArray();
        $userRole = $userProfile ? ($userProfile['user_role'] ?? 'Non-TWG') : 'Non-TWG';

        return $this->respond([
            'status' => 200,
            'message' => 'Login successful',
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'user_role' => $userRole,
                'full_name' => $user['full_name']
            ]
        ]);
    }

    public function register()
    {
        $data = $this->request->getJSON(true) ?: $this->request->getPost();

        $turnstileCheck = $this->verifyTurnstile($data['turnstile_token'] ?? '');
        if ($turnstileCheck['success'] !== true) {
            return $this->fail('Invalid security token: ' . ($turnstileCheck['error'] ?? 'Unknown Error') . 
                (isset($turnstileCheck['cloudflare_response']) ? ' | CF Details: ' . json_encode($turnstileCheck['cloudflare_response']) : ''));
        }

        $rules = [
            'fullname' => 'required',
            'department' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $email = $data['email'];
        $username = strtolower(str_replace(' ', '_', explode('@', $email)[0]));
        
        $userModel = new UserModel();
        
        // Check if user exists
        if ($userModel->findByIdentity($email)) {
            return $this->failResourceExists('A user with that email or username already exists');
        }

        // Map department input (could be ID or string)
        $db = \Config\Database::connect();
        $departmentInput = $data['department'];
        $officeId = null;

        if (is_numeric($departmentInput)) {
            $officeId = (int) $departmentInput;
        } else {
            // Clean the input to prevent redundant entries (spaces, casing)
            $cleanName = trim($departmentInput);
            $cleanName = preg_replace('/\s+/', ' ', $cleanName);
            $cleanName = ucwords(strtolower($cleanName));

            $office = $db->table('office_units')->where('office_name', $cleanName)->get()->getRowArray();
            if ($office) {
                $officeId = $office['office_id'];
            } else {
                $db->table('office_units')->insert(['office_name' => $cleanName]);
                $officeId = $db->insertID();
            }
        }

        // Map user_role from frontend to actual database role
        $role = 'college'; // Default
        if (isset($data['user_role'])) {
            switch ($data['user_role']) {
                case 'Director': $role = 'admin'; break;
                case 'Staff': $role = 'gad_staff'; break;
                case 'TWG':
                case 'Non-TWG':
                default:
                    $role = 'college'; break;
            }
        }

        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $role,
            'full_name' => $data['fullname'],
            'student_id' => $data['university_id'] ?? null,
            'office_id' => $officeId
        ];

        if ($userModel->insert($userData)) {
            $newUserId = $userModel->insertID();

            // Save detailed information to user_profiles table
            $db->table('user_profiles')->insert([
                'user_id' => $newUserId,
                'first_name' => $data['first_name'] ?? '',
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'] ?? '',
                'user_role' => $data['user_role'] ?? 'Non-TWG',
                'office_unit_id' => $officeId
            ]);

            return $this->respondCreated(['message' => 'Account created successfully. Please log in.']);
        }

        return $this->fail('Unable to create account. Please try again later.');
    }

    public function forgotPassword()
    {
        $data = $this->request->getJSON(true) ?: $this->request->getPost();
        
        $turnstileCheck = $this->verifyTurnstile($data['turnstile_token'] ?? '');
        if ($turnstileCheck['success'] !== true) {
            return $this->fail('Invalid security token: ' . ($turnstileCheck['error'] ?? 'Unknown Error') . 
                (isset($turnstileCheck['cloudflare_response']) ? ' | CF Details: ' . json_encode($turnstileCheck['cloudflare_response']) : ''));
        }

        if (empty($data['email'])) {
            return $this->fail('Email is required');
        }

        $email = $data['email'];
        $userModel = new UserModel();
        $user = $userModel->findByIdentity($email);

        if (!$user) {
            // For security, don't reveal if email exists or not, just return success
            return $this->respond(['message' => 'If your email is registered, you will receive a reset link shortly.']);
        }

        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $userModel->update($user['id'], [
            'reset_token' => hash('sha256', $token),
            'reset_token_expires_at' => $expiresAt
        ]);

        // Brevo requires an API Key (starts with xkeysib-) for HTTP requests, not an SMTP password
        $apiKey = getenv('BREVO_API_KEY') ?: env('BREVO_API_KEY') ?: getenv('SMTP_PASS') ?: env('SMTP_PASS') ?: env('email.SMTPPass') ?: '';
        $fromEmail = getenv('FROM_EMAIL') ?: env('FROM_EMAIL') ?: env('email.fromEmail') ?: 'gadims.bsu.bsit@gmail.com';

        $frontendUrl = rtrim(getenv('FRONTEND_URL') ?: env('FRONTEND_URL') ?: getenv('app.baseURL') ?: env('app.baseURL') ?: 'http://localhost:5173', '/');
        $resetLink = $frontendUrl . '/reset-password?token=' . $token;

        $message = "
        <html>
        <head><title>Password Reset</title></head>
        <body>
            <h2>Password Reset Request</h2>
            <p>You recently requested to reset your password for your GAD AMS account. Click the button below to reset it.</p>
            <p><a href='{$resetLink}' style='background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Reset Password</a></p>
            <p>If you did not request a password reset, please ignore this email. This link is only valid for the next hour.</p>
        </body>
        </html>
        ";

        // Render's Free Tier firewall completely blocks native SMTP (Ports 25, 465, 587)
        // To bypass this, we use Brevo's HTTP REST API over standard Port 443 (HTTPS)
        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . $apiKey,
            'content-type: application/json'
        ]);
        
        $payload = [
            'sender'      => ['name' => 'GAD AMS System', 'email' => $fromEmail],
            'to'          => [['email' => $email]],
            'subject'     => 'Password Reset Request',
            'htmlContent' => $message
        ];
        
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        
        // Disable SSL verification for maximum compatibility across environments
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return $this->respond(['message' => 'If your email is registered, you will receive a reset link shortly.']);
        } else {
            log_message('error', 'Brevo API Error: ' . $response . ' cURL Error: ' . $curlError);
            return $this->fail('Unable to send reset email. HTTP Code: ' . $httpCode . ' Error: ' . $response . ' cURL: ' . $curlError);
        }
    }

    public function resetPassword()
    {
        $data = $this->request->getJSON(true) ?: $this->request->getPost();
        
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $tokenHash = hash('sha256', $data['token']);
        
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $tokenHash)
                          ->where('reset_token_expires_at >=', date('Y-m-d H:i:s'))
                          ->first();

        if (!$user) {
            return $this->fail('Invalid or expired password reset token.');
        }

        $updateData = [
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_token_expires_at' => null
        ];

        if ($userModel->update($user['id'], $updateData)) {
            return $this->respond(['message' => 'Password has been successfully reset. You can now log in.']);
        }

        return $this->fail('Failed to reset password. Please try again.');
    }

    public function logout()
    {
        return $this->respond(['message' => 'Logout successful']);
    }

    public function handleOptions()
    {
        return $this->respond(['status' => 200]);
    }

    public function getOffices() {
        $db = \Config\Database::connect();
        // The frontend expects unit_id and unit_name, but our DB has office_id and office_name
        $offices = $db->table('office_units')->get()->getResultArray();
        $mappedOffices = array_map(function($o) {
            return [
                'unit_id' => $o['office_id'],
                'unit_name' => $o['office_name']
            ];
        }, $offices);
        return $this->respond($mappedOffices);
    }

    public function addOffice() {
        $data = $this->request->getJSON(true);
        $db = \Config\Database::connect();
        
        // Clean the input to prevent redundant entries (spaces, casing)
        $cleanName = trim($data['unit_name']);
        $cleanName = preg_replace('/\s+/', ' ', $cleanName);
        $cleanName = ucwords(strtolower($cleanName));

        // Check if the cleaned name already exists to prevent duplication
        $existing = $db->table('office_units')->where('office_name', $cleanName)->get()->getRowArray();
        if ($existing) {
            return $this->respondCreated(['new_id' => $existing['office_id']]);
        }

        $db->table('office_units')->insert(['office_name' => $cleanName]);
        return $this->respondCreated(['new_id' => $db->insertID()]);
    }

    public function getAllUsers() {
        $db = \Config\Database::connect();
        $users = $db->table('users')
            ->select('users.id, users.email, users.full_name, users.role, users.office_id, users.deleted_at, user_profiles.user_role, office_units.office_name')
            ->join('user_profiles', 'user_profiles.user_id = users.id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->get()
            ->getResultArray();
        return $this->respond($users);
    }

    protected function verifyTurnstile($token)
    {
        if (empty($token)) return ['success' => false, 'error' => 'Token is empty'];

        $secret = env('TURNSTILE_SECRET_KEY');
        if (empty($secret)) {
            return ['success' => false, 'error' => 'Secret key is missing from backend .env'];
        }

        $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
        $data = [
            'secret' => $secret,
            'response' => $token,
            'remoteip' => $this->request->getIPAddress()
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        
        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return ['success' => false, 'error' => 'cURL Error: ' . $error];
        }
        
        curl_close($ch);

        $response = json_decode($result, true);
        if (isset($response['success']) && $response['success'] === true) {
            return ['success' => true];
        }

        return [
            'success' => false, 
            'error' => 'Cloudflare rejected token', 
            'cloudflare_response' => $response
        ];
    }
}

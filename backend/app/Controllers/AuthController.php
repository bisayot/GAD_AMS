<?php

namespace App\Controllers;

use App\Libraries\JwtHelper;
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
            return $this->failUnauthorized("Invalid username/email or password.");
        }

        if (!empty($user['deleted_at'])) {
            return $this->failUnauthorized("Your account has been suspended. Please contact the GAD Office.");
        }

        $role = strtolower($user['role'] ?? '');
        $isStaffOrAdmin = in_array($role, ['admin', 'gad_staff', 'superadmin'], true);
        $maxAttempts = $isStaffOrAdmin ? 5 : 3;
        $now = time();

        if (!empty($user['locked_until'])) {
            $lockedUntil = strtotime($user['locked_until']);
            if ($lockedUntil !== false && $lockedUntil > $now) {
                return $this->respond([
                    'status' => 423,
                    'message' => $isStaffOrAdmin
                        ? 'Your account is locked for 15 minutes after 5 failed attempts.'
                        : 'Your account is locked for 15 minutes after 3 failed attempts. Click Forgot Password to reset your password or contact the GAD Office.',
                    'locked_until' => $user['locked_until'],
                    'lockout_options' => $isStaffOrAdmin ? ['wait', 'forgot_password'] : ['forgot_password', 'contact_office'],
                ], 423);
            }

            $userModel->update($user['id'], ['locked_until' => null, 'failed_login_attempts' => 0]);
            $user['failed_login_attempts'] = 0;
        }

        if (!password_verify($password, $user['password'])) {
            $failedAttempts = ((int) ($user['failed_login_attempts'] ?? 0)) + 1;
            $update = ['failed_login_attempts' => $failedAttempts];
            if ($failedAttempts >= $maxAttempts) {
                $update['locked_until'] = date('Y-m-d H:i:s', $now + (15 * 60));
                $userModel->update($user['id'], $update);

                return $this->respond([
                    'status' => 423,
                    'message' => $isStaffOrAdmin
                        ? 'Your account is locked for 15 minutes after 5 failed attempts.'
                        : 'Your account is locked for 15 minutes after 3 failed attempts. Click Forgot Password to reset your password or contact the GAD Office.',
                    'locked_until' => $update['locked_until'],
                    'lockout_options' => $isStaffOrAdmin ? ['wait', 'forgot_password'] : ['forgot_password', 'contact_office'],
                ], 423);
            }

            $userModel->update($user['id'], $update);
            $remaining = $maxAttempts - $failedAttempts;
            return $this->failUnauthorized("Invalid username/email or password. You have {$remaining} attempt(s) remaining before your account is locked for 15 minutes.");
        }

        // Self-registered accounts require GAD staff approval before first login.
        $approvalStatus = strtolower($user['approval_status'] ?? 'approved');
        if ($approvalStatus === 'pending') {
            return $this->respond([
                'status'  => 403,
                'message' => 'Your account is still pending approval. The GAD staff must approve your registration before you can log in. You will receive an email once a decision has been made.',
            ], 403);
        }

        $userModel->update($user['id'], [
            'last_login' => date('Y-m-d H:i:s'),
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);

        $userRole = $user['profile_role'] ?? 'Non-TWG';

        \App\Models\ActivityLogModel::log($user['id'], 'Login', $user['full_name'] . " logged in");

        // Trigger automated data retention cleanup (Pseudo-Cron, throttled to once every 24 hours via file lock)
        if (in_array(strtolower($user['role']), ['admin', 'gad_staff', 'superadmin'])) {
            $cleanupLock = WRITEPATH . 'cache/data_retention_cleanup.lock';
            $shouldCleanup = !file_exists($cleanupLock) || (time() - filemtime($cleanupLock) > 86400);
            if ($shouldCleanup) {
                @touch($cleanupLock);
                try {
                    \App\Libraries\DataRetentionService::runCleanup();
                } catch (\Throwable $e) {
                    log_message('error', 'Auto-cleanup failed during login: ' . $e->getMessage());
                }
            }
        }

        // Fetch profile and office information
        $db = \Config\Database::connect();
        $profile = $db->tableExists('user_profiles') 
            ? ($db->table('user_profiles')->where('user_id', $user['id'])->get()->getRowArray() ?: [])
            : [];
        $office = $user['office_id'] ? $db->table('office_units')->where('office_id', $user['office_id'])->get()->getRowArray() : null;

        // Generate a signed JWT — this is the real auth token the frontend will
        // attach to every subsequent request via Authorization: Bearer <token>
        $token = JwtHelper::generate([
            'sub'       => $user['id'],
            'role'      => $user['role'],
            'user_role' => $userRole,
        ]);

        return $this->respond([
            'status'  => 200,
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => [
                'id'              => $user['id'],
                'username'        => $user['username'],
                'email'           => $user['email'] ?? '',
                'role'            => $user['role'],
                'user_role'       => $userRole,
                'full_name'       => $user['full_name'],
                'office_id'       => $user['office_id'],
                'office_name'     => $office['office_name'] ?? '',
                'location'        => $office['location'] ?? 'La Trinidad Campus',
                'office_acronym'  => $office['office_acronym'] ?? '',
                'first_name'      => $profile['first_name'] ?? $user['first_name'] ?? '',
                'middle_name'     => $profile['middle_name'] ?? $user['middle_name'] ?? '',
                'last_name'       => $profile['last_name'] ?? $user['last_name'] ?? '',
                'sex'             => $profile['sex'] ?? '',
                'profile_picture' => $profile['profile_picture'] ?? '',
                'position'        => $profile['position'] ?? '',
                'department'      => $profile['department'] ?? '',
                'student_id'      => $profile['student_id'] ?? $user['student_id'] ?? '',
                'year_level'      => $profile['year_level'] ?? $user['year_level'] ?? ''
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
            'fullname' => [
                'label' => 'Full Name',
                'rules' => 'required',
                'errors' => ['required' => 'Full Name is required.']
            ],
            'department' => [
                'label' => 'College / Office',
                'rules' => 'required',
                'errors' => ['required' => 'Please select or specify your College / Office.']
            ],
            'department_name' => [
                'label' => 'Department',
                'rules' => 'required',
                'errors' => ['required' => 'Department is required.']
            ],
            'university_id' => [
                'label' => 'Student ID / ID Number',
                'rules' => 'required',
                'errors' => ['required' => 'Student ID / ID Number is required.']
            ],
            'year_level' => [
                'label' => 'Year Level',
                'rules' => 'required',
                'errors' => ['required' => 'Year Level is required.']
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email is required.',
                    'valid_email' => 'Please provide a valid email address.'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[8]|regex_match[/[A-Z]/]|regex_match[/[a-z]/]|regex_match[/[0-9]/]|regex_match[/[^A-Za-z0-9]/]',
                'errors' => [
                    'required' => 'Password is required.',
                    'min_length' => 'Password must be at least 8 characters long.',
                    'regex_match' => 'Password must contain uppercase, lowercase, number, and special character.'
                ]
            ],
            'confirm_password' => [
                'label' => 'Confirm Password',
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Confirm password is required.',
                    'matches' => 'Passwords do not match.'
                ]
            ]
        ];

        // Ensure aliases are populated before validation
        if (empty($data['department']) && !empty($data['office_id'])) {
            $data['department'] = $data['office_id'];
        }
        if (empty($data['university_id']) && !empty($data['student_id'])) {
            $data['university_id'] = $data['student_id'];
        }

        if (!$this->validateData($data, $rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $email = trim($data['email']);
        $username = strtolower(str_replace(' ', '_', explode('@', $email)[0]));
        
        // Map user_role from frontend to actual database role.
        // Self-registration is ONLY allowed for TWG and Non-TWG.
        // Admin (Director) and Staff accounts must be created by an admin
        // through the User Management panel — never through public registration.
        $userRole = $data['user_role'] ?? 'Non-TWG';
        $role = ($userRole === 'TWG') ? 'twg' : 'non-twg';

        // Enforce @bsu.edu.ph institutional email for TWG registration
        if ($role === 'twg' || strtoupper($userRole) === 'TWG') {
            if (!preg_match('/@bsu\.edu\.ph$/i', $email)) {
                return $this->fail(['email' => 'TWG accounts require a valid institutional email (@bsu.edu.ph).']);
            }
        }

        $userModel = new UserModel();
        
        // Check if user exists
        if ($userModel->findByIdentity($email)) {
            return $this->failResourceExists('A user with that email or username already exists');
        }

        // Map department input (could be ID or string, optional)
        $db = \Config\Database::connect();
        $departmentInput = !empty($data['department']) ? $data['department'] : (!empty($data['office_id']) ? $data['office_id'] : null);
        $officeId = null;
        $cleanOfficeName = null;

        $studentId = trim($data['university_id'] ?? $data['student_id'] ?? '') ?: null;
        $yearLevel = trim($data['year_level'] ?? '') ?: null;
        $departmentName = !empty($data['department_name']) ? trim($data['department_name']) : null;
        $position = !empty($data['position']) ? trim($data['position']) : null;

        if ($departmentName && (strlen($departmentName) <= 3 || preg_match('/^(dept|it|cs|hrm|educ|agri)\.?$/i', $departmentName))) {
            return $this->fail(['department_name' => "Abbreviations such as '{$departmentName}' are not allowed for department. Please enter the full name."]);
        }

        if (!empty($departmentInput)) {
            if (is_numeric($departmentInput)) {
                $officeId = (int) $departmentInput;
            } else {
                // Clean the input to prevent redundant entries (spaces, casing)
                $cleanName = trim($departmentInput);
                $cleanName = preg_replace('/\s+/', ' ', $cleanName);
                $cleanName = ucwords(strtolower($cleanName));

                if (!empty($cleanName)) {
                    // 1. Exact match on office_name
                    $office = $db->table('office_units')->where('office_name', $cleanName)->get()->getRowArray();

                    // 2. Acronym match on office_acronym (e.g. CHET, CA, CTE, CAS)
                    if (!$office) {
                        $office = $db->table('office_units')->where('office_acronym', strtoupper($cleanName))->get()->getRowArray();
                    }

                    // 3. Computed acronym match from full names (e.g. "CHET" -> "College of Home Economics and Technology")
                    if (!$office) {
                        $allOffices = $db->table('office_units')->get()->getResultArray();
                        $upperClean = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $cleanName));
                        foreach ($allOffices as $o) {
                            $words = preg_split('/\s+/', trim($o['office_name']));
                            $majorWords = array_filter($words, function($w) {
                                return !in_array(strtolower($w), ['of', 'and', 'the', 'for', 'in', 'at', '&']);
                            });
                            $acronym = '';
                            foreach ($majorWords as $mw) {
                                $acronym .= strtoupper($mw[0] ?? '');
                            }
                            if ($acronym === $upperClean && strlen($acronym) >= 2) {
                                $office = $o;
                                break;
                            }
                        }
                    }

                    if ($office) {
                        $officeId = $office['office_id'];
                    } else {
                        // Check if input is an abbreviation or acronym (e.g. <= 5 chars or abbreviation tokens)
                        if (strlen($cleanName) <= 5 || preg_match('/^[a-zA-Z](\.[a-zA-Z])+\.?$/', $cleanName) || preg_match('/^(dept|coll|off|admin|tech|univ|chet|ca|cte|cas|cis|hrmo|spmo)\.?$/i', $cleanName)) {
                            return $this->fail(['department' => "Abbreviations such as '{$cleanName}' are not allowed. Please select the full official College / Office name."]);
                        }
                        $cleanOfficeName = $cleanName;
                    }
                }
            }
        }

        // Atomic registration transaction: office creation, user record, and profile record
        $db->transStart();
        try {
            if (!$officeId && !empty($cleanOfficeName)) {
                $location = !empty($data['campus_location']) ? trim($data['campus_location']) : 'La Trinidad Campus';
                $db->table('office_units')->insert([
                    'office_name' => $cleanOfficeName,
                    'location' => $location
                ]);
                $officeId = $db->insertID();
            }

            $officeNameDisplay = 'None / Unspecified';
            if ($officeId) {
                $officeRow = $db->table('office_units')->where('office_id', $officeId)->get()->getRowArray();
                $officeNameDisplay = $officeRow ? $officeRow['office_name'] : 'Unknown Office';
            }

            if ($departmentName) {
                if ($officeNameDisplay !== 'None / Unspecified') {
                    $officeNameDisplay .= " ({$departmentName})";
                } else {
                    $officeNameDisplay = $departmentName;
                }
            }

            $userData = [
                'username' => $username,
                'email' => $email,
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'role' => $role,
                'full_name' => $data['fullname'],
                'first_name' => $data['first_name'] ?? '',
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'] ?? '',
                'profile_role' => $data['user_role'] ?? 'Non-TWG',
                'student_id' => $studentId,
                'year_level' => $yearLevel,
                'office_id' => $officeId,
                // New self-registrations must be approved by GAD staff before login.
                'approval_status' => 'pending'
            ];

            if (!$userModel->insert($userData)) {
                $db->transRollback();
                return $this->fail('Unable to create account. Please try again later.');
            }

            $newUserId = $userModel->insertID();

            // Create 1-to-1 Profile if user_profiles table exists
            if ($db->tableExists('user_profiles')) {
                $db->table('user_profiles')->insert([
                    'user_id' => $newUserId,
                    'first_name' => $data['first_name'] ?? '',
                    'middle_name' => $data['middle_name'] ?? null,
                    'last_name' => $data['last_name'] ?? '',
                    'sex' => $data['sex'] ?? null,
                    'position' => $position,
                    'department' => $departmentName,
                    'student_id' => $studentId,
                    'year_level' => $yearLevel,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->fail('Unable to create account. Please try again later.');
            }
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Registration transaction error: ' . $e->getMessage());
            return $this->fail('Unable to create account. Please try again later.');
        }

        $authUserId = \App\Libraries\JwtHelper::getAuthUserId($this->request);
        $actionUserId = $authUserId > 0 ? $authUserId : $newUserId;
        \App\Models\ActivityLogModel::log($actionUserId, 'Register User', 'registered a new user: ' . $data['fullname']);

        // Notify admins and staff
        try {
            $notificationModel = new \App\Models\NotificationModel();
            $adminsAndStaff = $userModel->whereIn('role', ['admin', 'gad_staff'])->findAll();
            $frontendUrl = rtrim(getenv('FRONTEND_URL') ?: env('FRONTEND_URL') ?: getenv('app.baseURL') ?: env('app.baseURL') ?: 'http://localhost:5173', '/');

            $fromEmail = env('FROM_EMAIL') ?: getenv('FROM_EMAIL') ?: 'gadims.bsu.bsit@gmail.com';
            $fromEmail = trim($fromEmail, '"\'');

            $escName = esc($data['fullname']);
            $escEmail = esc($email);
            $escRole = esc($data['user_role']);
            $escOffice = esc($officeNameDisplay);

            foreach ($adminsAndStaff as $notifUser) {
                // 1. In-site Notification
                $notificationModel->insert([
                    'user_id' => $notifUser['id'],
                    'title' => 'New User Registration — Action Required',
                    'message' => "A new user {$data['fullname']} ({$email}) has registered and is awaiting approval. Role: {$data['user_role']}, Office: {$officeNameDisplay}. Please approve or decline.",
                    'type' => 'info',
                    'link' => $manageLink,
                    'is_read' => 0
                ]);

                // 2. Direct Personalized Email Notification
                if (!empty($notifUser['email'])) {
                    $recipientName = esc($notifUser['first_name'] ?? $notifUser['full_name'] ?? 'Colleague');
                    $emailService = \Config\Services::email(false);
                    $emailService->setTo($notifUser['email']);
                    $emailService->setFrom($fromEmail, 'GAD AMS System');
                    $emailService->setSubject('New User Registration — Approval Required');

                    $emailMessage = "
                    <html>
                    <head><title>New User Registration</title></head>
                    <body style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <h2 style='color: #9333ea;'>New User Registration</h2>
                        <p>Hello <strong>{$recipientName}</strong>,</p>
                        <p>A new user has registered in the GAD AMS system and is awaiting your approval.</p>
                        <ul>
                            <li><strong>Name:</strong> {$escName}</li>
                            <li><strong>Email:</strong> {$escEmail}</li>
                            <li><strong>Role:</strong> {$escRole}</li>
                            <li><strong>Department/Office:</strong> {$escOffice}</li>
                        </ul>
                        <p>Please review the registration and choose to <strong>Approve</strong> or <strong>Decline</strong> it here:</p>
                        <p><a href='{$frontendUrl}{$manageLink}' style='display: inline-block; background-color: #9333ea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Review Registration</a></p>
                    </body>
                    </html>
                    ";

                    $emailService->setMessage($emailMessage);
                    try {
                        @$emailService->send();
                    } catch (\Throwable $e) {
                        log_message('error', 'Failed sending registration email to ' . $notifUser['email'] . ': ' . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error sending registration notifications: ' . $e->getMessage());
        }

        return $this->respondCreated(['message' => 'Your account has been created and is pending approval by the GAD staff. You will receive an email once your account has been approved.']);
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

        $apiKey = env('BREVO_API_KEY') ?: getenv('BREVO_API_KEY');
        $isProduction = env('CI_ENVIRONMENT') === 'production' || getenv('CI_ENVIRONMENT') === 'production';
        
        $fromEmail = env('FROM_EMAIL') ?: getenv('FROM_EMAIL') ?: env('email.fromEmail') ?: 'gadims.bsu.bsit@gmail.com';
        $fromEmail = trim($fromEmail, '"\'');

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

        /*
        // --- RENDER WORKAROUND (COMMENTED OUT) ---
        if ($apiKey && $isProduction) {
            // Render's Free Tier firewall completely blocks native SMTP (Ports 25, 465, 587)
            // To bypass this, we use Brevo's HTTP REST API over standard Port 443 (HTTPS)
            $ch = curl_init('https://api.brevo.com/v3/smtp/email');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'accept: application/json',
                'api-key: ' . trim($apiKey, '"\''),
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
        } else {
        */
            // Fallback to CodeIgniter SMTP
            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setSubject('Password Reset Request');
            $emailService->setMessage($message);
            
            try {
                if ($emailService->send()) {
                    return $this->respond(['message' => 'If your email is registered, you will receive a reset link shortly.']);
                } else {
                    return $this->fail('Unable to send reset email. Local SMTP failed.');
                }
            } catch (\Exception $e) {
                log_message('error', 'Local SMTP Error: ' . $e->getMessage());
                return $this->fail('Unable to send reset email. Local SMTP exception.');
            }
        // }
    }

    public function resetPassword()
    {
        $data = $this->request->getJSON(true) ?: $this->request->getPost();
        
        $rules = [
            'token' => 'required',
            'password' => ['label' => 'Password', 'rules' => 'required|min_length[8]|regex_match[/[A-Z]/]|regex_match[/[a-z]/]|regex_match[/[0-9]/]|regex_match[/[^A-Za-z0-9]/]'],
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
            'reset_token_expires_at' => null,
            'failed_login_attempts' => 0,
            'locked_until' => null,
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

        // Auto-cleanup accidental abbreviation entry 'Chet' if it was registered
        try {
            $bogus = $db->table('office_units')->where('office_name', 'Chet')->get()->getRowArray();
            if ($bogus) {
                $realChet = $db->table('office_units')
                    ->like('office_name', 'Home Economics')
                    ->get()
                    ->getRowArray();
                if ($realChet) {
                    $db->table('users')->where('office_id', $bogus['office_id'])->update(['office_id' => $realChet['office_id']]);
                    $db->table('office_units')->where('office_id', $bogus['office_id'])->delete();
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'Cleanup bogus office failed: ' . $e->getMessage());
        }

        // The frontend expects unit_id and unit_name, but our DB has office_id and office_name
        $offices = $db->table('office_units')->orderBy('office_name', 'ASC')->get()->getResultArray();
        $mappedOffices = array_map(function($o) {
            return [
                'unit_id' => $o['office_id'],
                'unit_name' => $o['office_name'],
                'location' => $o['location'] ?? 'La Trinidad Campus',
                'office_acronym' => $o['office_acronym'] ?? ''
            ];
        }, $offices);
        return $this->respond($mappedOffices);
    }

    public function addOffice() {
        $data = $this->request->getJSON(true);
        $db = \Config\Database::connect();
        
        // Clean the input to prevent redundant entries (spaces, casing)
        $cleanName = trim($data['unit_name'] ?? '');
        $cleanName = preg_replace('/\s+/', ' ', $cleanName);
        $cleanName = ucwords(strtolower($cleanName));
        $location = !empty($data['location']) ? trim($data['location']) : 'La Trinidad Campus';

        if (empty($cleanName)) {
            return $this->fail('Office name is required.');
        }

        // Reject abbreviations / acronyms
        if (strlen($cleanName) <= 5 || preg_match('/^[a-zA-Z](\.[a-zA-Z])+\.?$/', $cleanName) || preg_match('/^(dept|coll|off|admin|tech|univ|chet|ca|cte|cas|cis|hrmo|spmo)\.?$/i', $cleanName)) {
            return $this->fail("Abbreviations such as '{$cleanName}' are not allowed. Please enter the full official college or office name.");
        }

        // Check if the cleaned name already exists to prevent duplication
        $existing = $db->table('office_units')->where('office_name', $cleanName)->get()->getRowArray();
        if ($existing) {
            return $this->respondCreated(['new_id' => $existing['office_id']]);
        }

        $db->table('office_units')->insert([
            'office_name' => $cleanName,
            'location' => $location
        ]);
        return $this->respondCreated(['new_id' => $db->insertID()]);
    }

    public function getAllUsers() {
        $db = \Config\Database::connect();
        $builder = $db->table('users')
            ->select('users.id, users.email, users.full_name, users.role, users.office_id, users.deleted_at, users.created_at, users.last_login, users.profile_role as user_role, users.approval_status, office_units.office_name, office_units.location as campus_location')
            ->select('(SELECT COUNT(*) FROM activity_design WHERE activity_design.user_id = users.id) as ad_count')
            ->select('(SELECT COUNT(*) FROM accomplishment_report WHERE accomplishment_report.user_id = users.id) as ar_count')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left');

        if ($db->tableExists('user_profiles')) {
            $builder->select('user_profiles.position, user_profiles.department, user_profiles.sex, COALESCE(user_profiles.student_id, users.student_id) as student_id, COALESCE(user_profiles.year_level, users.year_level) as year_level')
                ->join('user_profiles', 'user_profiles.user_id = users.id', 'left');
        } else {
            $builder->select('NULL as position, NULL as department, NULL as sex, users.student_id, users.year_level');
        }

        $users = $builder->get()->getResultArray();
        return $this->respond($users);
    }

    public function getHumanChallenge()
    {
        $wordBank = [
            'SUMMIT', 'BREEZE', 'SILVER', 'HARBOR', 'FOREST',
            'PLANET', 'CANYON', 'BRIDGE', 'STREAM', 'CASTLE',
            'GOLDEN', 'SHADOW', 'BRIGHT', 'FALCON', 'MEADOW',
            'VALLEY', 'SPIRIT', 'SHIELD', 'ORANGE', 'PURPLE',
            'BEACON', 'FLOWER', 'SPRING', 'WINTER', 'SUMMER',
            'AUTUMN', 'GARDEN', 'RIVER', 'ISLAND', 'TEMPLE',
            'GALAXY', 'ANCHOR', 'PENCIL', 'WINDOW', 'VOYAGE',
            'MIRROR', 'CLOVER', 'TIGER', 'NATURE', 'DESERT',
            'SUNSET', 'CRYSTAL', 'CORAL', 'COSMOS', 'HARMONY',
            'THUNDER', 'VELVET', 'ZENITH', 'AURORA', 'COMPASS'
        ];

        $k1 = array_rand($wordBank);
        do {
            $k2 = array_rand($wordBank);
        } while ($k1 === $k2);

        $words = $wordBank[$k1] . ' ' . $wordBank[$k2];
        $time = time();

        $secret = env('JWT_SECRET') ?: env('TURNSTILE_SECRET_KEY') ?: 'gad_ams_secret_fallback_key';
        $sig = hash_hmac('sha256', "{$words}|{$time}", $secret);

        $challengeKey = base64_encode(json_encode([
            't' => $time,
            's' => $sig
        ]));

        $imageBase64 = $this->renderCaptchaImage($words);

        return $this->respond([
            'challenge_key' => $challengeKey,
            'image'         => $imageBase64
        ]);
    }

    /**
     * Render distorted CAPTCHA image using PHP GD
     */
    private function renderCaptchaImage(string $text): string
    {
        $width = 260;
        $height = 60;
        $image = imagecreatetruecolor($width, $height);

        // Dark background (#121218)
        $bgColor = imagecolorallocate($image, 18, 18, 24);
        imagefill($image, 0, 0, $bgColor);

        // Add subtle anti-bot noise lines
        for ($i = 0; $i < 5; $i++) {
            $lineColor = imagecolorallocatealpha($image, rand(120, 200), rand(80, 160), rand(200, 255), rand(75, 95));
            imagesetthickness($image, rand(1, 2));
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lineColor);
        }

        // Add scattered noise dots
        for ($i = 0; $i < 50; $i++) {
            $dotColor = imagecolorallocatealpha($image, rand(140, 220), rand(100, 180), rand(210, 255), rand(60, 90));
            imagesetpixel($image, rand(0, $width - 1), rand(0, $height - 1), $dotColor);
        }

        // Palette of neon/pastel colors
        $colors = [
            imagecolorallocate($image, 192, 132, 252), // purple
            imagecolorallocate($image, 129, 140, 248), // indigo
            imagecolorallocate($image, 56, 189, 248),  // sky
            imagecolorallocate($image, 244, 114, 182), // pink
            imagecolorallocate($image, 167, 139, 250), // violet
            imagecolorallocate($image, 232, 121, 249), // fuchsia
            imagecolorallocate($image, 96, 165, 250),  // blue
        ];

        $ttfPath = defined('APPPATH') ? realpath(APPPATH . '../../frontend/public/pdfjs/web/standard_fonts/LiberationSans-Bold.ttf') : false;
        $hasTtf = function_exists('imagettftext') && $ttfPath && file_exists($ttfPath);

        $len = strlen($text);
        $charSpacing = (int)($width / ($len + 2));
        $startX = (int)($charSpacing * 0.9);

        for ($i = 0; $i < $len; $i++) {
            $char = $text[$i];
            $color = $colors[$i % count($colors)];

            if ($hasTtf) {
                $angle = rand(-12, 12);
                $fontSize = 17;
                $y = rand(38, 44);
                imagettftext($image, $fontSize, $angle, $startX, $y, $color, $ttfPath, $char);
            } else {
                $y = rand(20, 25);
                imagechar($image, 5, $startX, $y, $char, $color);
            }
            $startX += $charSpacing;
        }

        ob_start();
        imagepng($image);
        $pngData = ob_get_clean();
        imagedestroy($image);

        return 'data:image/png;base64,' . base64_encode($pngData);
    }

    /**
     * Verify human challenge answer and return a single-use verified token
     */
    public function verifyHumanChallengeEndpoint()
    {
        $body = $this->request->getJSON(true) ?? $this->request->getPost();
        $challengeKey = $body['key'] ?? null;
        $answer = trim((string)($body['answer'] ?? ''));

        if (!$challengeKey || empty($answer)) {
            return $this->respond([
                'success' => false,
                'message' => 'Challenge key and answer are required.'
            ], 400);
        }

        $keyData = json_decode(base64_decode($challengeKey), true);
        if (!$keyData || !isset($keyData['t'], $keyData['s'])) {
            return $this->respond([
                'success' => false,
                'message' => 'Invalid challenge key.'
            ], 400);
        }

        $time = (int)$keyData['t'];
        $sig = $keyData['s'];
        $currentTime = time();

        // 10 minutes expiry window (600s), allow 60s future drift
        if (($currentTime - $time) > 600 || ($time - $currentTime) > 60) {
            return $this->respond([
                'success' => false,
                'message' => 'Human verification expired. Please refresh the challenge.'
            ], 400);
        }

        $secret = env('JWT_SECRET') ?: env('TURNSTILE_SECRET_KEY') ?: 'gad_ams_secret_fallback_key';
        $expectedSig = hash_hmac('sha256', "{$answer}|{$time}", $secret);

        if (!hash_equals($expectedSig, $sig)) {
            return $this->respond([
                'success' => false,
                'message' => 'Words do not match. Please verify spelling, spaces, and UPPERCASE letters strictly.'
            ], 400);
        }

        // Issue a single-use verified token signed by backend
        $nonce = bin2hex(random_bytes(16));
        $verifiedAt = time();
        $verifiedSig = hash_hmac('sha256', "{$nonce}|{$verifiedAt}", $secret);

        $tokenPayload = base64_encode(json_encode([
            'n' => $nonce,
            't' => $verifiedAt,
            's' => $verifiedSig
        ]));

        $verifiedToken = 'hc_verified:' . $tokenPayload;

        return $this->respond([
            'success' => true,
            'message' => 'Human verification passed.',
            'verified_token' => $verifiedToken
        ]);
    }

    protected function verifyTurnstile($token)
    {
        if (empty($token)) return ['success' => false, 'error' => 'Token is empty'];

        // Fallback custom human checker token
        if (strpos($token, 'hc_verified:') === 0 || strpos($token, 'hc_fallback:') === 0) {
            return $this->verifyHumanChallenge($token);
        }

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
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); // Force IPv4: avoids 2-5s dual-stack DNS timeouts on Windows/XAMPP
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);        // fail after 15 s
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);  // fail connection after 8 s
        // SSL certificate verification is intentionally left ON (default)
        // so PHP validates Cloudflare's certificate against its CA store.

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

    protected function verifyHumanChallenge($token)
    {
        $secret = env('JWT_SECRET') ?: env('TURNSTILE_SECRET_KEY') ?: 'gad_ams_secret_fallback_key';

        if (strpos($token, 'hc_verified:') === 0) {
            $payloadStr = substr($token, strlen('hc_verified:'));
            $payload = json_decode(base64_decode($payloadStr), true);

            if (!$payload || !isset($payload['n'], $payload['t'], $payload['s'])) {
                return ['success' => false, 'error' => 'Malformed human verification token'];
            }

            $nonce = $payload['n'];
            $time  = (int)$payload['t'];
            $sig   = $payload['s'];
            $currentTime = time();

            // 10 minutes expiry window (600s), allow 60s future drift
            if (($currentTime - $time) > 600 || ($time - $currentTime) > 60) {
                return ['success' => false, 'error' => 'Human verification token expired. Please refresh.'];
            }

            $expectedSig = hash_hmac('sha256', "{$nonce}|{$time}", $secret);
            if (!hash_equals($expectedSig, $sig)) {
                return ['success' => false, 'error' => 'Human verification signature mismatch.'];
            }

            // Single-use replay protection: check if nonce was already used
            $cacheKey = 'hc_nonce_' . $nonce;
            if (cache($cacheKey)) {
                return ['success' => false, 'error' => 'Human verification token has already been used. Please refresh.'];
            }
            cache()->save($cacheKey, 1, 600);

            return ['success' => true];
        }

        // Legacy format fallback: hc_fallback:
        $payloadStr = substr($token, strlen('hc_fallback:'));
        $payload = json_decode(base64_decode($payloadStr), true);

        if (!$payload || !isset($payload['key'], $payload['answer'])) {
            return ['success' => false, 'error' => 'Malformed human verification payload'];
        }

        $keyData = json_decode(base64_decode($payload['key']), true);
        if (!$keyData || !isset($keyData['t'], $keyData['s'])) {
            return ['success' => false, 'error' => 'Invalid human verification key'];
        }

        $time = (int)$keyData['t'];
        $sig = $keyData['s'];
        $currentTime = time();

        if (($currentTime - $time) > 600 || ($time - $currentTime) > 60) {
            return ['success' => false, 'error' => 'Human verification expired. Please refresh and try again.'];
        }

        $userAnswer = trim($payload['answer']);
        $expectedSig = hash_hmac('sha256', "{$userAnswer}|{$time}", $secret);

        if (!hash_equals($expectedSig, $sig)) {
            return ['success' => false, 'error' => 'Human verification mismatch. Words must match strictly.'];
        }

        // Single-use replay protection on signature
        $cacheKey = 'hc_sig_' . $sig;
        if (cache($cacheKey)) {
            return ['success' => false, 'error' => 'Human verification challenge has already been used. Please refresh.'];
        }
        cache()->save($cacheKey, 1, 600);

        return [
            'success' => true
        ];
    }
}

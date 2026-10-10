<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class UserManagementController extends ResourceController
{
    protected $format = 'json';

    public function create()
    {
        $data = $this->request->getJSON(true) ?: $this->request->getPost();

        $rules = [
            'full_name' => 'required',
            'email'     => 'required|valid_email',
            'password'  => [
                'label' => 'Password',
                'rules' => 'required|min_length[8]|regex_match[/[A-Z]/]|regex_match[/[a-z]/]|regex_match[/[0-9]/]|regex_match[/[^A-Za-z0-9]/]'
            ],
            'user_role' => 'required',
            'office_id' => 'required|numeric'
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $email = $data['email'];
        $baseUsername = strtolower(str_replace(' ', '_', explode('@', $email)[0]));
        $username = $baseUsername;
        
        $userModel = new \App\Models\UserModel();
        
        if ($userModel->findByIdentity($email)) {
            return $this->failResourceExists('A user with that email already exists');
        }

        // Ensure username is unique
        $counter = 1;
        while ($userModel->where('username', $username)->first()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $role = 'non-twg'; 
        switch ($data['user_role']) {
            case 'Director': $role = 'admin'; break;
            case 'Staff': $role = 'gad_staff'; break;
            case 'TWG': $role = 'twg'; break;
            case 'Non-TWG': $role = 'non-twg'; break;
            default:
                $role = 'non-twg'; break;
        }

        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $role,
            'full_name' => $data['full_name'],
            'office_id' => $data['office_id'],
            'profile_role' => $data['user_role'],
            'student_id' => trim($data['student_id'] ?? '') ?: null,
            'year_level' => $data['year_level'] ?? null
        ];

        if ($userModel->insert($userData)) {
            $newUserId = $userModel->insertID();

            $db = \Config\Database::connect();
            if ($db->tableExists('user_profiles')) {
                $profileModel = new \App\Models\UserProfileModel();
                $profileData = [
                    'user_id' => $newUserId,
                    'first_name' => trim($data['first_name'] ?? ''),
                    'middle_name' => trim($data['middle_name'] ?? '') ?: null,
                    'last_name' => trim($data['last_name'] ?? ''),
                    'sex' => $data['sex'] ?? null,
                    'position' => trim($data['position'] ?? '') ?: null,
                    'department' => trim($data['department'] ?? '') ?: null,
                    'student_id' => trim($data['student_id'] ?? '') ?: null,
                    'year_level' => $data['year_level'] ?? null,
                ];
                $profileModel->insert($profileData);
            }

            $actionUserId = $this->request->getHeaderLine('X-User-Id');
            if ($actionUserId) {
                \App\Models\ActivityLogModel::log($actionUserId, 'Register User', 'created a new user: ' . $data['full_name']);
            }

            return $this->respondCreated(['success' => true, 'message' => 'User created successfully.']);
        }

        return $this->fail('Unable to create user.');
    }

    public function update($id = null)
    {
        if (!$id) return $this->fail('User ID required');

        $data = $this->request->getJSON(true) ?: $this->request->getPost();

        $rules = [
            'full_name' => 'required',
            'email' => 'required|valid_email',
            'user_role' => 'required',
            'office_id' => 'required|numeric'
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($id);
        if (!$user) return $this->failNotFound('User not found');

        if ($data['email'] !== $user['email']) {
            if ($userModel->findByIdentity($data['email'])) {
                return $this->failResourceExists('A user with that email already exists');
            }
        }

        $role = 'non-twg'; 
        switch ($data['user_role']) {
            case 'Director': $role = 'admin'; break;
            case 'Staff': $role = 'gad_staff'; break;
            case 'TWG': $role = 'twg'; break;
            case 'Non-TWG': $role = 'non-twg'; break;
            default:
                $role = 'non-twg'; break;
        }

        $updateData = [
            'email' => $data['email'],
            'full_name' => $data['full_name'],
            'role' => $role,
            'office_id' => $data['office_id'],
            'profile_role' => $data['user_role']
        ];

        if (array_key_exists('student_id', $data)) {
            $updateData['student_id'] = trim($data['student_id'] ?? '') ?: null;
        }
        if (array_key_exists('year_level', $data)) {
            $updateData['year_level'] = $data['year_level'] ?: null;
        }

        if (!empty($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $userModel->update($id, $updateData);

        $db = \Config\Database::connect();
        if ($db->tableExists('user_profiles')) {
            $profileModel = new \App\Models\UserProfileModel();
            $existingProfile = $profileModel->getProfileByUserId($id);
            $profileUpdate = [];
            if (isset($data['sex'])) $profileUpdate['sex'] = $data['sex'] ?: null;
            if (isset($data['position'])) $profileUpdate['position'] = trim($data['position']) ?: null;
            if (isset($data['department'])) $profileUpdate['department'] = trim($data['department']) ?: null;
            if (isset($data['student_id'])) $profileUpdate['student_id'] = trim($data['student_id']) ?: null;
            if (isset($data['year_level'])) $profileUpdate['year_level'] = $data['year_level'] ?: null;
            if (isset($data['first_name'])) $profileUpdate['first_name'] = trim($data['first_name']) ?: null;
            if (isset($data['middle_name'])) $profileUpdate['middle_name'] = trim($data['middle_name']) ?: null;
            if (isset($data['last_name'])) $profileUpdate['last_name'] = trim($data['last_name']) ?: null;

            if (!empty($profileUpdate)) {
                if ($existingProfile) {
                    $profileModel->update($existingProfile['id'], $profileUpdate);
                } else {
                    $profileUpdate['user_id'] = $id;
                    $profileModel->insert($profileUpdate);
                }
            }
        }

        $actionUserId = $this->request->getHeaderLine('X-User-Id');
        if ($actionUserId) {
            \App\Models\ActivityLogModel::log($actionUserId, 'Update User', 'updated user account: ' . $data['full_name']);
        }

        return $this->respond(['success' => true, 'message' => 'User updated successfully.']);
    }

    public function suspend($id = null)
    {
        if (!$id) return $this->fail('User ID required');
        
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $id)->get()->getRowArray();
        if (!$user) return $this->failNotFound('User not found');

        $payload = $this->request->jwtPayload ?? null;
        $callerId = $payload['sub'] ?? null;

        if (in_array(strtolower($user['role']), ['admin', 'superadmin'], true)) {
            return $this->fail('Cannot suspend an Administrator account.');
        }

        $actionUserId = $callerId ?: $this->request->getHeaderLine('X-User-Id');

        $db->table('users')->where('id', $id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
        
        if ($actionUserId) {
            \App\Models\ActivityLogModel::log($actionUserId, 'Suspend User', 'suspended user account: ' . $user['full_name']);
        }
        
        return $this->respond(['success' => true, 'message' => 'User suspended']);
    }

    public function restore($id = null)
    {
        if (!$id) return $this->fail('User ID required');
        
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $id)->get()->getRowArray();
        if (!$user) return $this->failNotFound('User not found');

        $payload = $this->request->jwtPayload ?? null;
        $actionUserId = $payload['sub'] ?? $this->request->getHeaderLine('X-User-Id');

        $db->table('users')->where('id', $id)->update(['deleted_at' => null]);
        
        if ($actionUserId) {
            \App\Models\ActivityLogModel::log($actionUserId, 'Restore User', 'restored user account: ' . $user['full_name']);
        }
        
        return $this->respond(['success' => true, 'message' => 'User restored']);
    }

    public function delete($id = null)
    {
        if (!$id) return $this->fail('User ID required');
        
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $id)->get()->getRowArray();
        if (!$user) return $this->failNotFound('User not found');

        $payload = $this->request->jwtPayload ?? null;
        $callerId = $payload['sub'] ?? null;

        if (in_array(strtolower($user['role']), ['admin', 'superadmin'], true)) {
            return $this->fail('Cannot delete an Administrator account.');
        }

        $actionUserId = $callerId ?: $this->request->getHeaderLine('X-User-Id');

        // Remove login credentials to prevent access but keep the ID and name for data integrity
        $db->table('users')->where('id', $id)->update([
            'email' => null,
            'password' => null,
            'remember_token' => null,
            'role' => 'deleted',
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($actionUserId) {
            \App\Models\ActivityLogModel::log($actionUserId, 'Delete User Credentials', 'permanently deleted credentials for user: ' . $user['full_name']);
        }
        
        return $this->respond(['success' => true, 'message' => 'User credentials deleted']);
    }


    public function getProfile()
    {
        $payload = $this->request->jwtPayload ?? null;
        $userId = $payload['sub'] ?? ($payload['id'] ?? null);
        if (!$userId) return $this->failUnauthorized('Unauthorized: Valid token required.');

        $db = \Config\Database::connect();
        $user = $db->table('users')
            ->select('users.id, users.username, users.email, users.full_name, users.role, users.profile_role, users.office_id, office_units.office_name, office_units.location as campus_location, office_units.office_acronym')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->where('users.id', $userId)
            ->get()
            ->getRowArray();

        if (!$user) return $this->failNotFound('User not found');

        $profile = $db->tableExists('user_profiles')
            ? ($db->table('user_profiles')->where('user_id', $userId)->get()->getRowArray() ?: [])
            : [];

        return $this->respond([
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'full_name' => $user['full_name'],
                'email' => $user['email'] ?? '',
                'role' => $user['role'],
                'user_role' => $user['profile_role'] ?? 'Non-TWG',
                'office_id' => $user['office_id'],
                'office_name' => $user['office_name'] ?? 'N/A',
                'location' => $user['campus_location'] ?? 'La Trinidad Campus',
                'office_acronym' => $user['office_acronym'] ?? '',
                'first_name' => $profile['first_name'] ?? '',
                'middle_name' => $profile['middle_name'] ?? '',
                'last_name' => $profile['last_name'] ?? '',
                'sex' => $profile['sex'] ?? '',
                'profile_picture' => $profile['profile_picture'] ?? '',
                'position' => $profile['position'] ?? '',
                'department' => $profile['department'] ?? '',
                'student_id' => $profile['student_id'] ?? '',
                'year_level' => $profile['year_level'] ?? '',
            ]
        ]);
    }

    public function getUserProfile($id = null)
    {
        if (!$id) return $this->fail('User ID required');

        $db = \Config\Database::connect();
        $user = $db->table('users')
            ->select('users.id, users.username, users.email, users.full_name, users.role, users.profile_role, users.office_id, users.student_id as user_student_id, users.year_level as user_year_level, office_units.office_name, office_units.location as campus_location, office_units.office_acronym')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->where('users.id', $id)
            ->get()
            ->getRowArray();

        if (!$user) return $this->failNotFound('User not found');

        $profile = $db->tableExists('user_profiles')
            ? ($db->table('user_profiles')->where('user_id', $id)->get()->getRowArray() ?: [])
            : [];

        $studentId = !empty($profile['student_id']) ? $profile['student_id'] : ($user['user_student_id'] ?? '');
        $yearLevel = !empty($profile['year_level']) ? $profile['year_level'] : ($user['user_year_level'] ?? '');

        return $this->respond([
            'success' => true,
            'data' => [
                'id' => $user['id'],
                'full_name' => $user['full_name'],
                'email' => $user['email'] ?? '',
                'role' => $user['role'],
                'user_role' => $user['profile_role'] ?? 'Non-TWG',
                'office_name' => $user['office_name'] ?? 'N/A',
                'location' => $user['campus_location'] ?? 'La Trinidad Campus',
                'office_acronym' => $user['office_acronym'] ?? '',
                'first_name' => $profile['first_name'] ?? '',
                'middle_name' => $profile['middle_name'] ?? '',
                'last_name' => $profile['last_name'] ?? '',
                'sex' => $profile['sex'] ?? 'Not specified',
                'profile_picture' => $profile['profile_picture'] ?? '',
                'position' => $profile['position'] ?? '',
                'department' => $profile['department'] ?? '',
                'student_id' => $studentId,
                'year_level' => $yearLevel,
            ]
        ]);
    }

    public function updateProfile()
    {
        $payload = $this->request->jwtPayload ?? null;
        $userId = $payload['sub'] ?? ($payload['id'] ?? null);
        if (!$userId) return $this->failUnauthorized('Unauthorized: Valid token required.');

        $data = null;
        try {
            $data = $this->request->getJSON(true);
        } catch (\Throwable $e) {
            $data = null;
        }
        if (!$data) {
            $data = $this->request->getPost() ?: [];
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        if (!$user) return $this->failNotFound('User not found');

        $db = \Config\Database::connect();
        $profileModel = new \App\Models\UserProfileModel();
        $existingProfile = $profileModel->getProfileByUserId($userId);

        // 1. Handle Avatar Removal or Upload
        if (isset($data['remove_avatar']) && $data['remove_avatar']) {
            if ($existingProfile && !empty($existingProfile['profile_picture'])) {
                $oldFile = FCPATH . ltrim($existingProfile['profile_picture'], '/\\');
                if (is_file($oldFile)) {
                    @unlink($oldFile);
                }
            }
            $olderAvatars = glob(FCPATH . 'uploads/avatars/avatar_' . $userId . '_*');
            if ($olderAvatars) {
                foreach ($olderAvatars as $oldAvatar) {
                    if (is_file($oldAvatar)) {
                        @unlink($oldAvatar);
                    }
                }
            }
            if ($existingProfile) {
                $profileModel->update($existingProfile['id'], ['profile_picture' => null]);
            }
            \App\Models\ActivityLogModel::log($userId, 'Update Profile', 'removed profile picture');
            return $this->respond(['success' => true, 'message' => 'Profile picture removed successfully', 'avatar_url' => '']);
        }

        $avatarFile = $this->request->getFile('profile_picture');
        if ($avatarFile && $avatarFile->isValid() && !$avatarFile->hasMoved()) {
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $mimeType = $avatarFile->getMimeType();
            $ext = strtolower($avatarFile->guessExtension() ?: $avatarFile->getExtension());

            if (!in_array($mimeType, $allowedMimes) || !in_array($ext, $allowedExts)) {
                return $this->fail('Invalid image format. Only JPG, PNG, WEBP, and GIF are allowed.');
            }

            if ($avatarFile->getSizeByUnit('mb') > 3) {
                return $this->fail('Image file exceeds the 3MB size limit.');
            }

            if ($existingProfile && !empty($existingProfile['profile_picture'])) {
                $oldFile = FCPATH . ltrim($existingProfile['profile_picture'], '/\\');
                if (is_file($oldFile)) {
                    @unlink($oldFile);
                }
            }
            $olderAvatars = glob(FCPATH . 'uploads/avatars/avatar_' . $userId . '_*');
            if ($olderAvatars) {
                foreach ($olderAvatars as $oldAvatar) {
                    if (is_file($oldAvatar)) {
                        @unlink($oldAvatar);
                    }
                }
            }

            if (!is_dir(FCPATH . 'uploads/avatars')) {
                mkdir(FCPATH . 'uploads/avatars', 0755, true);
            }

            $newName = 'avatar_' . $userId . '_' . time() . '.' . $ext;
            $avatarFile->move(FCPATH . 'uploads/avatars', $newName);
            $avatarPath = 'uploads/avatars/' . $newName;

            if ($existingProfile) {
                $profileModel->update($existingProfile['id'], ['profile_picture' => $avatarPath]);
            } else {
                $profileModel->insert(['user_id' => $userId, 'profile_picture' => $avatarPath]);
            }

            \App\Models\ActivityLogModel::log($userId, 'Update Profile', 'uploaded a new profile picture');
            return $this->respond(['success' => true, 'message' => 'Profile picture updated successfully', 'avatar_url' => $avatarPath]);
        }

        // 2. Handle Personal & Designation Information Update
        if (
            isset($data['first_name']) || isset($data['last_name']) || 
            isset($data['position']) || isset($data['department']) || 
            isset($data['sex']) || isset($data['student_id']) || 
            isset($data['year_level']) || isset($data['full_name']) || 
            isset($data['office_id']) || !empty($data['new_office_name'])
        ) {
            $userUpdate = [];

            if (isset($data['first_name']) || isset($data['last_name']) || isset($data['full_name'])) {
                $firstName = trim($data['first_name'] ?? ($existingProfile['first_name'] ?? ''));
                $middleName = trim($data['middle_name'] ?? ($existingProfile['middle_name'] ?? ''));
                $lastName = trim($data['last_name'] ?? ($existingProfile['last_name'] ?? ''));

                $fullName = trim($firstName . ' ' . $middleName . ' ' . $lastName);
                if (empty($fullName) && !empty($data['full_name'])) {
                    $fullName = trim($data['full_name']);
                }
                $fullName = preg_replace('/\s+/', ' ', $fullName);
                if (!empty($fullName)) {
                    $userUpdate['full_name'] = $fullName;
                }
            }

            if (!empty($data['new_office_name'])) {
                $cleanOffice = trim($data['new_office_name']);
                $cleanOffice = preg_replace('/\s+/', ' ', $cleanOffice);
                $cleanOffice = ucwords(strtolower($cleanOffice));
                $loc = !empty($data['campus_location']) ? trim($data['campus_location']) : 'La Trinidad Campus';
                
                $foundOffice = $db->table('office_units')->where('office_name', $cleanOffice)->get()->getRowArray();
                if ($foundOffice) {
                    $userUpdate['office_id'] = (int) $foundOffice['office_id'];
                } else {
                    $db->table('office_units')->insert([
                        'office_name' => $cleanOffice,
                        'location' => $loc,
                        'office_acronym' => $data['office_acronym'] ?? null
                    ]);
                    $userUpdate['office_id'] = (int) $db->insertID();
                }
            } else if (isset($data['office_id']) && is_numeric($data['office_id'])) {
                $userUpdate['office_id'] = (int) $data['office_id'];
            }

            if (!empty($userUpdate)) {
                $userModel->update($userId, $userUpdate);
            }

            $profileUpdate = [];
            if (isset($data['first_name'])) $profileUpdate['first_name'] = trim($data['first_name']);
            if (isset($data['middle_name'])) $profileUpdate['middle_name'] = trim($data['middle_name']) ?: null;
            if (isset($data['last_name'])) $profileUpdate['last_name'] = trim($data['last_name']);
            if (isset($data['sex'])) $profileUpdate['sex'] = $data['sex'] ?: null;
            if (isset($data['position'])) $profileUpdate['position'] = trim($data['position']) ?: null;
            if (isset($data['department'])) $profileUpdate['department'] = trim($data['department']) ?: null;
            if (isset($data['student_id'])) $profileUpdate['student_id'] = trim($data['student_id']) ?: null;
            if (isset($data['year_level'])) $profileUpdate['year_level'] = $data['year_level'] ?: null;

            if (!empty($profileUpdate)) {
                if ($existingProfile) {
                    $profileModel->update($existingProfile['id'], $profileUpdate);
                } else {
                    $profileUpdate['user_id'] = $userId;
                    $profileModel->insert($profileUpdate);
                }
            }

            \App\Models\ActivityLogModel::log($userId, 'Update Profile', 'updated profile and designation details');
            return $this->respond(['success' => true, 'message' => 'Profile updated successfully']);
        }

        // 3. Handle Email Update
        if (isset($data['email'])) {
            $rules = ['email' => 'required|valid_email'];
            if (!$this->validateData($data, $rules)) {
                return $this->respond(['success' => false, 'message' => 'Invalid email format']);
            }
            if ($data['email'] !== $user['email'] && $userModel->findByIdentity($data['email'])) {
                return $this->respond(['success' => false, 'message' => 'Email already in use']);
            }
            $userModel->update($userId, ['email' => $data['email']]);
            \App\Models\ActivityLogModel::log($userId, 'Update Profile', 'updated their email address');
            return $this->respond(['success' => true, 'message' => 'Email updated successfully']);
        }

        // 4. Handle Password Update
        if (isset($data['current_password']) && isset($data['new_password'])) {
            if (!password_verify($data['current_password'], $user['password'])) {
                return $this->respond(['success' => false, 'message' => 'Incorrect current password']);
            }
            $passRules = [
                'new_password' => [
                    'label' => 'New Password',
                    'rules' => 'required|min_length[8]|regex_match[/[A-Z]/]|regex_match[/[a-z]/]|regex_match[/[0-9]/]|regex_match[/[^A-Za-z0-9]/]'
                ]
            ];
            if (!$this->validateData($data, $passRules)) {
                return $this->respond(['success' => false, 'message' => implode(' ', $this->validator->getErrors())]);
            }
            $userModel->update($userId, ['password' => password_hash($data['new_password'], PASSWORD_DEFAULT)]);
            \App\Models\ActivityLogModel::log($userId, 'Update Profile', 'updated their password');
            return $this->respond(['success' => true, 'message' => 'Password updated successfully']);
        }

        return $this->respond(['success' => false, 'message' => 'No valid update data provided']);
    }
}

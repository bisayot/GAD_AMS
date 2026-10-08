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
            'profile_role' => $data['user_role']
        ];

        if ($userModel->insert($userData)) {
            $newUserId = $userModel->insertID();

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

        // Staff can now modify admin accounts

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

        if (!empty($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $userModel->update($id, $updateData);

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

        $actionUserId = $this->request->getHeaderLine('X-User-Id');

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

        $actionUserId = $this->request->getHeaderLine('X-User-Id');

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

        $actionUserId = $this->request->getHeaderLine('X-User-Id');

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
        $userId = $payload['sub'] ?? ($payload['id'] ?? $this->request->getHeaderLine('X-User-Id'));
        if (!$userId) return $this->failUnauthorized('Not logged in');

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        if (!$user) return $this->failNotFound('User not found');

        return $this->respond([
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'full_name' => $user['full_name'],
                'role' => $user['role']
            ]
        ]);
    }

    public function updateProfile()
    {
        $payload = $this->request->jwtPayload ?? null;
        $userId = $payload['sub'] ?? ($payload['id'] ?? $this->request->getHeaderLine('X-User-Id'));
        if (!$userId) return $this->failUnauthorized('Not logged in');

        $data = $this->request->getJSON(true) ?: $this->request->getPost();
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        if (!$user) return $this->failNotFound('User not found');

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

        if (isset($data['full_name'])) {
            $rules = ['full_name' => 'required|min_length[2]'];
            if (!$this->validateData($data, $rules)) {
                return $this->respond(['success' => false, 'message' => 'Invalid name format']);
            }
            $userModel->update($userId, ['full_name' => $data['full_name']]);
            \App\Models\ActivityLogModel::log($userId, 'Update Profile', 'updated their display name');
            return $this->respond(['success' => true, 'message' => 'Name updated successfully']);
        }

        if (isset($data['current_password']) && isset($data['new_password'])) {
            if (!password_verify($data['current_password'], $user['password'])) {
                return $this->respond(['success' => false, 'message' => 'Incorrect current password']);
            }
            // Enforce the same strong password policy as registration
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

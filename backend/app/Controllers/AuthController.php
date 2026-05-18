<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    protected $format = 'json';

    public function login()
    {
        $rules = [
            'identity' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $identity = $this->request->getVar('identity');
        $password = $this->request->getVar('password');

        $userModel = new UserModel();
        $user = $userModel->findByIdentity($identity);

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->failUnauthorized('Invalid username/email or password');
        }

        // In a real app, you'd generate a JWT here. 
        // For this demo, we'll just return user info.
        return $this->respond([
            'status' => 200,
            'message' => 'Login successful',
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ]
        ]);
    }

    public function register()
    {
        $rules = [
            'fullname' => 'required',
            'university_id' => 'required',
            'department' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $email = $this->request->getVar('email');
        $username = strtolower(str_replace(' ', '_', explode('@', $email)[0]));
        
        $userModel = new UserModel();
        
        // Check if user exists
        if ($userModel->findByIdentity($email)) {
            return $this->failResourceExists('A user with that email or username already exists');
        }

        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'role' => 'college', // Default role for registration
            'full_name' => $this->request->getVar('fullname'),
            'student_id' => $this->request->getVar('university_id'),
            'college' => $this->request->getVar('department')
        ];

        if ($userModel->insert($userData)) {
            return $this->respondCreated(['message' => 'Account created successfully. Please log in.']);
        }

        return $this->fail('Unable to create account. Please try again later.');
    }

    public function logout()
    {
        return $this->respond(['message' => 'Logout successful']);
    }
}

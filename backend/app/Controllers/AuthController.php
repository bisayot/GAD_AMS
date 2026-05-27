<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    protected $format = 'json';

    public function login()
    {
        // Accept JSON body or form-data
        $data = $this->request->getJSON(true) ?: $this->request->getPost();

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $email    = trim($data['email']);
        $password = $data['password'];

        $userModel = new UserModel();
        $user      = $userModel->findByEmail($email);

        if (!$user) {
            return $this->failUnauthorized('No account found with that email address.');
        }

        if (!password_verify($password, $user['password'])) {
            return $this->failUnauthorized('Incorrect password.');
        }

        // Build the display name from profile columns
        $displayName = trim($user['first_name'] . ' ' . ($user['middle_name'] ? $user['middle_name'][0] . '. ' : '') . $user['last_name']);

        return $this->respond([
            'status'  => 200,
            'message' => 'Login successful',
            'user'    => [
                'id'         => $user['id'],
                'email'      => $user['email'],
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'],
                'name'       => $displayName,
                'role'       => $user['user_role'],  // Director | Staff | TWG | Non-TWG
            ],
        ]);
    }

    public function register()
    {
        $data = $this->request->getJSON(true) ?: $this->request->getPost();

        $rules = [
            'first_name'       => 'required|max_length[100]',
            'last_name'        => 'required|max_length[100]',
            'middle_name'      => 'permit_empty|max_length[100]',
            'email'            => 'required|valid_email|max_length[255]',
            'user_role'        => 'required|in_list[Director,Staff,TWG,Non-TWG]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $email = trim($data['email']);

        $userModel = new UserModel();

        // Check for duplicate email
        if ($userModel->findByEmail($email)) {
            return $this->failResourceExists('An account with that email already exists.');
        }

        $userId = $userModel->registerUser(
            $email,
            password_hash($data['password'], PASSWORD_DEFAULT),
            [
                'first_name'     => trim($data['first_name']),
                'middle_name'    => isset($data['middle_name']) ? trim($data['middle_name']) : null,
                'last_name'      => trim($data['last_name']),
                'user_role'      => $data['user_role'],
                'office_unit_id' => isset($data['office_unit_id']) ? (int) $data['office_unit_id'] : null,
            ]
        );

        if (!$userId) {
            return $this->fail('Unable to create account. Please try again later.');
        }

        return $this->respondCreated(['message' => 'Account created successfully. Please log in.']);
    }

    public function logout()
    {
        return $this->respond(['message' => 'Logout successful']);
    }

    public function handleOptions()
    {
        return $this->respond(['status' => 200]);
    }
}

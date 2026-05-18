<?php
// src/Controllers/AuthController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\Session;

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
        Session::start();
    }

    public function showLogin() {
        if ($this->isLoggedIn()) {
            $this->redirectByRole(Session::get('user_role'));
        }
        $this->view('auth/login', [], false);
    }

    public function login() {
        $identity = trim($_POST['identity'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($identity === '' || $password === '') {
            $error = 'Please enter both username/email and password.';
            $this->view('auth/login', ['loginError' => $error], false);
            return;
        }

        $user = $this->userModel->findByIdentity($identity);

        if ($user && password_verify($password, $user['password'])) {
            Session::set('user_id', $user['id']);
            Session::set('username', $user['username']);
            Session::set('user_role', $user['role']);
            $this->redirectByRole($user['role']);
        } else {
            $error = 'Invalid credentials. Please try again.';
            $this->view('auth/login', ['loginError' => $error], false);
        }
    }

    public function logout() {
        Session::destroy();
        $this->redirect('/login');
    }

    private function isLoggedIn() {
        return Session::get('user_id') && 
               Session::get('username') && 
               Session::get('user_role');
    }

    private function redirectByRole($role) {
        switch ($role) {
            case 'admin':
                $this->redirect('/admin/dashboard');
                break;
            case 'college':
                $this->redirect('/college/dashboard');
                break;
            case 'gad_staff':
                $this->redirect('/staff/dashboard');
                break;
            default:
                $this->redirect('/');
                break;
        }
    }
}

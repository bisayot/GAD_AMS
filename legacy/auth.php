<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is authenticated and has a valid session
 * @return bool True if user is logged in with valid session data
 */
if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return !empty($_SESSION['user_id']) && 
               !empty($_SESSION['username']) && 
               !empty($_SESSION['user_role']) &&
               in_array($_SESSION['user_role'], ['admin', 'college', 'gad_staff'], true);
    }
}

/**
 * Require user to be logged in
 * Redirects to index.php if not authenticated
 */
if (!function_exists('require_login')) {
    function require_login() {
        if (!is_logged_in()) {
            header('Location: /GAD_v11/index.php');
            exit;
        }
    }
}

/**
 * Require user to have specific role(s)
 * @param array $roles Array of allowed roles
 */
if (!function_exists('require_role')) {
    function require_role(array $roles) {
        // Check if user is logged in
        if (!is_logged_in()) {
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Access Denied',
                'message' => 'Please log in to access this page.'
            ];
            header('Location: /GAD_v11/index.php');
            exit;
        }
        
        $userRole = $_SESSION['user_role'] ?? '';
        
        // Check if user has the required role
        if (!in_array($userRole, $roles, true)) {
            // Redirect to user's dashboard instead of showing 403
            $_SESSION['alert'] = [
                'type' => 'warning',
                'title' => 'Permission Denied',
                'message' => 'You do not have permission to access that page.'
            ];
            redirect_by_role($userRole);
        }
    }
}

/**
 * Redirect user to appropriate dashboard based on their role
 * @param string $role The user's role
 */
if (!function_exists('redirect_by_role')) {
    function redirect_by_role($role) {
        switch ($role) {
            case 'admin':
                header('Location: /GAD_v11/admin/dashboard.php');
                break;
            case 'college':
                header('Location: /GAD_v11/college/dashboard.php');
                break;
            case 'gad_staff':
                header('Location: /GAD_v11/staff/dashboard.php');
                break;
            default:
                header('Location: /GAD_v11/index.php');
                break;
        }
        exit;
    }
}

/**
 * Get the current logged-in user's information
 * @return array|null User data or null if not logged in
 */
if (!function_exists('get_current_user')) {
    function get_current_user() {
        if (!is_logged_in()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['user_role']
        ];
    }
}

/**
 * Check if current user has a specific role
 * @param string $role The role to check
 * @return bool True if user has the role
 */
if (!function_exists('has_role')) {
    function has_role($role) {
        return is_logged_in() && $_SESSION['user_role'] === $role;
    }
}

/**
 * Check if current user has any of the given roles
 * @param array $roles Array of roles to check
 * @return bool True if user has any of the roles
 */
if (!function_exists('has_any_role')) {
    function has_any_role(array $roles) {
        return is_logged_in() && in_array($_SESSION['user_role'], $roles, true);
    }
}

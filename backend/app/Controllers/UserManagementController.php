<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class UserManagementController extends ResourceController
{
    protected $format = 'json';

    public function suspend($id = null)
    {
        if (!$id) return $this->fail('User ID required');
        
        $db = \Config\Database::connect();
        $db->table('users')->where('id', $id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
        
        return $this->respond(['success' => true, 'message' => 'User suspended']);
    }

    public function restore($id = null)
    {
        if (!$id) return $this->fail('User ID required');
        
        $db = \Config\Database::connect();
        $db->table('users')->where('id', $id)->update(['deleted_at' => null]);
        
        return $this->respond(['success' => true, 'message' => 'User restored']);
    }

    public function permanentlyDelete($id = null)
    {
        if (!$id) return $this->fail('User ID required');
        
        $db = \Config\Database::connect();
        $db->table('users')->where('id', $id)->delete();
        $db->table('user_profiles')->where('user_id', $id)->delete();
        
        return $this->respond(['success' => true, 'message' => 'User permanently deleted']);
    }
}

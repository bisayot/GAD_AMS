<?php
// src/Models/User.php

namespace App\Models;

use App\Core\Model;

class User extends Model {
    public function findByIdentity($identity) {
        $sql = 'SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('ss', $identity, $identity);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

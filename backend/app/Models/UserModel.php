<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['email', 'password', 'remember_token'];

    protected $useTimestamps = false;

    /**
     * Find a user by email, joining user_profiles so we get name + role in one query.
     */
    public function findByEmail(string $email): ?array
    {
        return $this->db->table('users u')
            ->select('u.id, u.email, u.password, up.first_name, up.middle_name, up.last_name, up.user_role, up.office_unit_id')
            ->join('user_profiles up', 'up.user_id = u.id', 'left')
            ->where('u.email', $email)
            ->get()
            ->getRowArray();
    }

    /**
     * Register a new user: inserts into `users` then creates the linked `user_profiles` row.
     *
     * @param string $email
     * @param string $hashedPassword
     * @param array  $profile  ['first_name', 'middle_name', 'last_name', 'user_role', 'office_unit_id']
     * @return int|false  The new user ID, or false on failure.
     */
    public function registerUser(string $email, string $hashedPassword, array $profile)
    {
        $db = $this->db;

        // Insert into users
        $db->table('users')->insert([
            'email'    => $email,
            'password' => $hashedPassword,
        ]);

        $userId = $db->insertID();
        if (!$userId) {
            return false;
        }

        // Insert into user_profiles
        $db->table('user_profiles')->insert([
            'user_id'        => $userId,
            'first_name'     => $profile['first_name'],
            'middle_name'    => $profile['middle_name'] ?? null,
            'last_name'      => $profile['last_name'],
            'user_role'      => $profile['user_role'],
            'office_unit_id' => $profile['office_unit_id'] ?? null,
        ]);

        return $userId;
    }
}

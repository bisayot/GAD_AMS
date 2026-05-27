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

    // Only columns that actually exist in the `users` table
    protected $allowedFields    = ['email', 'password', 'remember_token'];

    // Timestamps
    protected $useTimestamps    = false;

    /**
     * Find a user by email (the only identity column in the `users` table).
     * Joins user_profiles so the caller gets role and name in one query.
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
     * Insert a new user row and its accompanying user_profiles row atomically.
     *
     * @param string $email
     * @param string $hashedPassword
     * @param array  $profile  ['first_name', 'middle_name', 'last_name', 'user_role', 'office_unit_id']
     * @return int|false  The new user ID on success, false on failure.
     */
    public function registerUser(string $email, string $hashedPassword, array $profile): int|false
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Insert into `users`
        $db->table('users')->insert([
            'email'    => $email,
            'password' => $hashedPassword,
        ]);
        $userId = $db->insertID();

        if (!$userId) {
            $db->transRollback();
            return false;
        }

        // Insert into `user_profiles`
        $db->table('user_profiles')->insert([
            'user_id'        => $userId,
            'first_name'     => $profile['first_name'],
            'middle_name'    => $profile['middle_name'] ?? null,
            'last_name'      => $profile['last_name'],
            'user_role'      => $profile['user_role'],
            'office_unit_id' => $profile['office_unit_id'] ?? null,
        ]);

        $db->transComplete();

        return $db->transStatus() ? $userId : false;
    }
}

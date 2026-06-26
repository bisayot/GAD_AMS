<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ActivityLogModel;

class ActivityLogController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $roleFilter = $this->request->getGet('role');
        $emailFilter = $this->request->getGet('email');
        $excludeAdmin = $this->request->getGet('exclude_admin');
        $userId = $this->request->getGet('user_id');

        $db = \Config\Database::connect();
        $builder = $db->table('activity_logs')
            ->select('activity_logs.*, users.full_name, users.email, users.role as system_role, user_profiles.user_role as custom_role, office_units.office_name')
            ->join('users', 'users.id = activity_logs.user_id', 'left')
            ->join('user_profiles', 'user_profiles.user_id = users.id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->orderBy('activity_logs.created_at', 'DESC');

        if ($roleFilter) {
            if (strtolower($roleFilter) === 'admin') {
                $builder->where('users.role', 'admin');
            } else {
                $builder->where('user_profiles.user_role', $roleFilter);
            }
        }

        if ($emailFilter) {
            $builder->where('users.email', $emailFilter);
        }

        if ($excludeAdmin === 'true') {
            $builder->where('users.role !=', 'admin');
        }

        if ($userId) {
            $builder->where('activity_logs.user_id', $userId);
        }

        $logs = $builder->get()->getResultArray();

        return $this->respond([
            'success' => true,
            'data' => $logs
        ]);
    }
}

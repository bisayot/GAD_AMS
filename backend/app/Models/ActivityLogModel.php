<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table            = 'activity_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'action', 'description', 'created_at'];

    // Dates
    protected $useTimestamps = false; // We set created_at manually or handle it

    /**
     * Helper method to easily insert an activity log
     */
    public static function log($userId, $action, $description = null)
    {
        $model = new self();
        $model->insert([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}

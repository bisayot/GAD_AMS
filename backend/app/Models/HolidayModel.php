<?php

namespace App\Models;

use CodeIgniter\Model;

class HolidayModel extends Model
{
    protected $table            = 'holidays';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['date', 'name', 'type'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'date' => 'required|valid_date|is_unique[holidays.date,id,{id}]',
        'name' => 'required|max_length[255]',
        'type' => 'required|in_list[public,school,custom]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}

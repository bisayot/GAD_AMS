<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsIecModel extends Model
{
    protected $table            = 'news_iec';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'description', 'image_path', 'tags', 'category', 'published_by'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'title'        => 'required|min_length[3]|max_length[255]',
        'category'     => 'required|in_list[News,IEC]',
        'published_by' => 'required|is_natural_no_zero',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}

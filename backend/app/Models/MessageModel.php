<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table            = 'messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'sender_id', 
        'recipient_id', 
        'message_text', 
        'document_type', 
        'document_id', 
        'is_read', 
        'is_announcement',
        'created_at',
        'parent_id',
        'deleted_by_sender_at',
        'deleted_by_recipient_at'
    ];

    protected $useTimestamps = false;
}

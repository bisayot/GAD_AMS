<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MessageModel;

class MessageController extends ResourceController
{
    protected $messageModel;

    public function __construct()
    {
        $this->messageModel = new MessageModel();
    }

    public function send()
    {
        $rules = [
            'sender_id' => 'required|integer',
            'to'        => 'required',
            'title'     => 'required|string',
            'message'   => 'required|string',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Validation failed', 
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(400);
        }

        $senderId = $this->request->getVar('sender_id');
        $to = $this->request->getVar('to'); // Expected array of user IDs
        $title = $this->request->getVar('title');
        $messageText = $this->request->getVar('message');
        $documentType = $this->request->getVar('document_type');
        $documentId = $this->request->getVar('document_id');
        $parentId = $this->request->getVar('parent_id');

        if (!is_array($to)) {
            $to = [$to];
        }

        if (is_array($documentId)) {
            $documentId = implode(',', $documentId);
        }

        $createdAt = date('Y-m-d H:i:s');
        $insertedCount = 0;

        foreach ($to as $recipientId) {
            $data = [
                'sender_id'     => $senderId,
                'recipient_id'  => $recipientId,
                'parent_id'     => $parentId ? $parentId : null,
                'title'         => $title,
                'message_text'  => $messageText,
                'document_type' => $documentType ? $documentType : null,
                'document_id'   => $documentId ? $documentId : null,
                'is_read'       => 0,
                'created_at'    => $createdAt
            ];

            if ($this->messageModel->insert($data)) {
                $insertedCount++;
            }
        }

        if ($insertedCount > 0) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "Message sent to $insertedCount recipient(s)."
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to send message.'
            ])->setStatusCode(500);
        }
    }

    public function getInbox($userId)
    {
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User ID required'])->setStatusCode(400);
        }

        // Fetch messages where recipient_id = $userId, grouped by thread
        $db = \Config\Database::connect();
        $sql = "
            SELECT messages.*, users.full_name, users.username, users.email, COALESCE(user_profiles.user_role, users.role) as role, users.office_id
            FROM messages
            INNER JOIN (
                SELECT MAX(id) as max_id
                FROM messages
                WHERE recipient_id = ? AND deleted_by_recipient_at IS NULL
                GROUP BY IFNULL(parent_id, id)
            ) latest ON messages.id = latest.max_id
            JOIN users ON users.id = messages.sender_id
            LEFT JOIN user_profiles ON user_profiles.user_id = users.id
            ORDER BY messages.created_at DESC
        ";
        $messages = $db->query($sql, [$userId])->getResultArray();

        // Format for frontend
        $formatted = array_map(function($msg) {
            $senderName = $msg['full_name'] ?? '';
            if (empty($senderName)) {
                $senderName = $msg['username'] ?? 'Unknown Sender';
            }

            $date = new \DateTime($msg['created_at']);
            $date->setTimezone(new \DateTimeZone('Asia/Manila'));

            return [
                'id' => $msg['id'],
                'sender' => $senderName,
                'sender_id' => $msg['sender_id'],
                'email' => $msg['email'],
                'role' => $msg['role'],
                'office_id' => $msg['office_id'],
                'date' => $date->format('M d, Y h:i A'),
                'title' => $msg['title'],
                'preview' => mb_strimwidth(strip_tags($msg['message_text']), 0, 100, "..."),
                'message' => $msg['message_text'],
                'document_type' => $msg['document_type'],
                'document_id' => $msg['document_id'],
                'is_read' => $msg['is_read'],
                'parent_id' => $msg['parent_id']
            ];
        }, $messages);

        return $this->response->setJSON([
            'success' => true,
            'data' => $formatted
        ]);
    }

    public function getSent($userId)
    {
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User ID required'])->setStatusCode(400);
        }

        // Fetch messages where sender_id = $userId, grouped by thread
        $db = \Config\Database::connect();
        $sql = "
            SELECT messages.*, users.full_name, users.username, users.email, COALESCE(user_profiles.user_role, users.role) as role, users.office_id
            FROM messages
            INNER JOIN (
                SELECT MAX(id) as max_id
                FROM messages
                WHERE sender_id = ? AND deleted_by_sender_at IS NULL
                GROUP BY IFNULL(parent_id, id)
            ) latest ON messages.id = latest.max_id
            JOIN users ON users.id = messages.recipient_id
            LEFT JOIN user_profiles ON user_profiles.user_id = users.id
            ORDER BY messages.created_at DESC
        ";
        $messages = $db->query($sql, [$userId])->getResultArray();

        // Format for frontend
        $formatted = array_map(function($msg) {
            $recipientName = $msg['full_name'] ?? '';
            if (empty($recipientName)) {
                $recipientName = $msg['username'] ?? 'Unknown Recipient';
            }

            $date = new \DateTime($msg['created_at']);
            $date->setTimezone(new \DateTimeZone('Asia/Manila'));

            return [
                'id' => $msg['id'],
                'sender' => $recipientName, // we use 'sender' key in frontend but it means 'other party'
                'sender_id' => $msg['recipient_id'],
                'email' => $msg['email'],
                'role' => $msg['role'],
                'office_id' => $msg['office_id'],
                'date' => $date->format('M d, Y h:i A'),
                'title' => $msg['title'],
                'preview' => $msg['message_text'],
                'document_type' => $msg['document_type'],
                'document_id' => $msg['document_id'],
                'is_read' => $msg['is_read'],
                'parent_id' => $msg['parent_id'],
                'message' => $msg['message_text']
            ];
        }, $messages);

        return $this->response->setJSON([
            'success' => true,
            'data' => $formatted
        ]);
    }
    
    public function markAsRead($messageId)
    {
        if ($this->messageModel->update($messageId, ['is_read' => 1])) {
            return $this->response->setJSON(['success' => true]);
        }
        return $this->response->setJSON(['success' => false])->setStatusCode(500);
    }

    public function getTrashed($userId)
    {
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User ID required'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        
        // Auto-cleanup: Permanently delete messages in trash older than 30 days
        $db->query("DELETE FROM messages WHERE (deleted_by_sender_at IS NOT NULL AND deleted_by_sender_at < DATE_SUB(NOW(), INTERVAL 30 DAY)) OR (deleted_by_recipient_at IS NOT NULL AND deleted_by_recipient_at < DATE_SUB(NOW(), INTERVAL 30 DAY))");

        $sql = "
            SELECT messages.*, users.full_name, users.username, users.email, COALESCE(user_profiles.user_role, users.role) as role, users.office_id, IF(messages.sender_id = ?, 'sent', 'received') as direction
            FROM messages
            INNER JOIN (
                SELECT MAX(id) as max_id
                FROM messages
                WHERE (recipient_id = ? AND deleted_by_recipient_at IS NOT NULL)
                   OR (sender_id = ? AND deleted_by_sender_at IS NOT NULL)
                GROUP BY IFNULL(parent_id, id)
            ) latest ON messages.id = latest.max_id
            JOIN users ON users.id = IF(messages.sender_id = ?, messages.recipient_id, messages.sender_id)
            LEFT JOIN user_profiles ON user_profiles.user_id = users.id
            ORDER BY messages.created_at DESC
        ";
        
        $messages = $db->query($sql, [$userId, $userId, $userId, $userId])->getResultArray();

        $formatted = array_map(function($msg) {
            $name = $msg['full_name'] ?? ($msg['username'] ?? 'Unknown');
            $date = new \DateTime($msg['created_at']);
            $date->setTimezone(new \DateTimeZone('Asia/Manila'));
            
            // For trash, show when it was deleted.
            $deletedAt = $msg['direction'] === 'sent' ? $msg['deleted_by_sender_at'] : $msg['deleted_by_recipient_at'];
            $deletedDate = new \DateTime($deletedAt);
            $deletedDate->setTimezone(new \DateTimeZone('Asia/Manila'));

            return [
                'id' => $msg['id'],
                'sender' => $name, // other party
                'sender_id' => $msg['direction'] === 'sent' ? $msg['recipient_id'] : $msg['sender_id'],
                'email' => $msg['email'],
                'role' => $msg['role'],
                'office_id' => $msg['office_id'],
                'date' => $date->format('M d, Y h:i A'),
                'deleted_date' => $deletedDate->format('M d, Y h:i A'),
                'title' => $msg['title'],
                'preview' => mb_strimwidth(strip_tags($msg['message_text']), 0, 100, "..."),
                'message' => $msg['message_text'],
                'document_type' => $msg['document_type'],
                'document_id' => $msg['document_id'],
                'is_read' => $msg['is_read'],
                'direction' => $msg['direction'],
                'parent_id' => $msg['parent_id']
            ];
        }, $messages);

        return $this->response->setJSON(['success' => true, 'data' => $formatted]);
    }

    public function trashMessage($messageId)
    {
        $json = $this->request->getJSON();
        $userId = $this->request->getVar('user_id') ?? ($json->user_id ?? null);
        if (!$userId) return $this->response->setJSON(['success' => false, 'message' => 'User ID required'])->setStatusCode(400);

        $msg = $this->messageModel->find($messageId);
        if (!$msg) return $this->response->setJSON(['success' => false, 'message' => 'Not found'])->setStatusCode(404);

        $threadId = $msg['parent_id'] ? $msg['parent_id'] : $msg['id'];
        $now = date('Y-m-d H:i:s');

        $db = \Config\Database::connect();
        
        // Soft delete all messages in this thread where the user is the sender
        $db->table('messages')->where('sender_id', $userId)
            ->groupStart()->where('id', $threadId)->orWhere('parent_id', $threadId)->groupEnd()
            ->update(['deleted_by_sender_at' => $now]);
            
        // Soft delete all messages in this thread where the user is the recipient
        $db->table('messages')->where('recipient_id', $userId)
            ->groupStart()->where('id', $threadId)->orWhere('parent_id', $threadId)->groupEnd()
            ->update(['deleted_by_recipient_at' => $now]);

        return $this->response->setJSON(['success' => true]);
    }

    public function restoreMessage($messageId)
    {
        $json = $this->request->getJSON();
        $userId = $this->request->getVar('user_id') ?? ($json->user_id ?? null);
        $userId = $json->user_id ?? $this->request->getVar('user_id');
        if (!$userId) return $this->response->setJSON(['success' => false, 'message' => 'User ID required'])->setStatusCode(400);

        $msg = $this->messageModel->find($messageId);
        if (!$msg) return $this->response->setJSON(['success' => false, 'message' => 'Not found'])->setStatusCode(404);

        $threadId = $msg['parent_id'] ? $msg['parent_id'] : $msg['id'];

        $db = \Config\Database::connect();
        
        $db->table('messages')->where('sender_id', $userId)
            ->groupStart()->where('id', $threadId)->orWhere('parent_id', $threadId)->groupEnd()
            ->update(['deleted_by_sender_at' => null]);
            
        $db->table('messages')->where('recipient_id', $userId)
            ->groupStart()->where('id', $threadId)->orWhere('parent_id', $threadId)->groupEnd()
            ->update(['deleted_by_recipient_at' => null]);

        return $this->response->setJSON(['success' => true]);
    }

    public function permanentlyDelete()
    {
        $json = $this->request->getJSON();
        $messageIds = $this->request->getVar('message_ids') ?? ($json->message_ids ?? []);
        $userId = $this->request->getVar('user_id') ?? ($json->user_id ?? null);
        
        if (!$userId || empty($messageIds)) return $this->response->setJSON(['success' => false])->setStatusCode(400);

        $db = \Config\Database::connect();
        
        // Find all thread IDs for the given messages
        $msgs = $db->table('messages')->whereIn('id', $messageIds)->get()->getResultArray();
        $threadIds = [];
        foreach($msgs as $m) {
            $threadIds[] = $m['parent_id'] ? $m['parent_id'] : $m['id'];
        }
        $threadIds = array_unique($threadIds);
        
        if (empty($threadIds)) return $this->response->setJSON(['success' => true]);

        // Permanently delete for sender
        $db->table('messages')->where('sender_id', $userId)
            ->groupStart()->whereIn('id', $threadIds)->orWhereIn('parent_id', $threadIds)->groupEnd()
            ->update(['deleted_by_sender_at' => '2000-01-01 00:00:00']);
            
        // Permanently delete for recipient
        $db->table('messages')->where('recipient_id', $userId)
            ->groupStart()->whereIn('id', $threadIds)->orWhereIn('parent_id', $threadIds)->groupEnd()
            ->update(['deleted_by_recipient_at' => '2000-01-01 00:00:00']);

        // Final cleanup of rows that are fully permanently deleted by both
        $db->query("DELETE FROM messages WHERE deleted_by_sender_at = '2000-01-01 00:00:00' AND deleted_by_recipient_at = '2000-01-01 00:00:00'");

        return $this->response->setJSON(['success' => true]);
    }

    public function getThread($messageId)
    {
        $db = \Config\Database::connect();
        
        // Find the root message by traversing parent_id upwards (simple 1-level or recursive)
        // Since we only do simple reply-chains, we can just fetch all messages that share the same parent_id, OR where id = parent_id.
        // Even better, find the top-level parent ID:
        $msg = $this->messageModel->find($messageId);
        if (!$msg) return $this->response->setJSON(['success' => false])->setStatusCode(404);
        
        $rootId = $msg['parent_id'] ? $msg['parent_id'] : $msg['id'];
        
        // Fetch root and all its direct descendants
        $builder = $db->table('messages');
        $builder->select('messages.*, users.full_name as sender_name, COALESCE(user_profiles.user_role, users.role) as sender_role');
        $builder->join('users', 'users.id = messages.sender_id');
        $builder->join('user_profiles', 'user_profiles.user_id = users.id', 'left');
        $builder->groupStart()
                    ->where('messages.id', $rootId)
                    ->orWhere('messages.parent_id', $rootId)
                ->groupEnd();
        $builder->orderBy('messages.created_at', 'ASC');
        
        $thread = $builder->get()->getResultArray();
        
        $formatted = array_map(function($m) {
            $date = new \DateTime($m['created_at']);
            $date->setTimezone(new \DateTimeZone('Asia/Manila'));
            return [
                'id' => $m['id'],
                'sender_id' => $m['sender_id'],
                'sender_name' => $m['sender_name'],
                'sender_role' => $m['sender_role'],
                'message' => $m['message_text'],
                'date' => $date->format('M d, Y h:i A'),
                'document_type' => $m['document_type'],
                'document_id' => $m['document_id']
            ];
        }, $thread);
        
        return $this->response->setJSON(['success' => true, 'data' => $formatted]);
    }

    public function getUnreadCount($userId)
    {
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User ID required'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('messages');
        $builder->where('recipient_id', $userId);
        $builder->where('is_read', 0);
        $builder->where('deleted_by_recipient_at IS NULL');
        
        $count = $builder->countAllResults();

        return $this->response->setJSON([
            'success' => true,
            'count' => $count
        ]);
    }
}

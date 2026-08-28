<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\NotificationModel;
use App\Libraries\NotificationService;
use App\Models\UserModel;

class NotificationController extends ResourceController
{
    private function getUserId()
    {
        return $this->request->getHeaderLine('X-User-Id');
    }

    /**
     * Get unread notifications for the logged in user
     */
    public function getUnread()
    {
        $userId = $this->getUserId();
        if (!$userId) {
            return $this->failUnauthorized('Not logged in');
        }

        $notificationModel = new NotificationModel();
        $notifications = $notificationModel->getUnread($userId);
        
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        if ($user) {
            foreach ($notifications as &$notif) {
                $notif['link'] = NotificationService::resolveLink($notif['link'], $user['role']);
            }
        }
        
        return $this->respond([
            'status' => 'success',
            'data' => $notifications
        ]);
    }

    /**
     * Get all notifications for the logged in user
     */
    public function getAll()
    {
        $userId = $this->getUserId();
        if (!$userId) {
            return $this->failUnauthorized('Not logged in');
        }

        $limit = $this->request->getGet('limit') ?: 50;

        $notificationModel = new NotificationModel();
        $notifications = $notificationModel->getAllForUser($userId, $limit);
        
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        if ($user) {
            foreach ($notifications as &$notif) {
                $notif['link'] = NotificationService::resolveLink($notif['link'], $user['role']);
            }
        }
        
        return $this->respond([
            'status' => 'success',
            'data' => $notifications
        ]);
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead($id)
    {
        $userId = $this->getUserId();
        if (!$userId) {
            return $this->failUnauthorized('Not logged in');
        }

        $notificationModel = new NotificationModel();
        $notification = $notificationModel->find($id);

        if (!$notification || $notification['user_id'] != $userId) {
            return $this->failNotFound('Notification not found');
        }

        $notificationModel->update($id, ['is_read' => 1]);

        return $this->respond([
            'status' => 'success',
            'message' => 'Marked as read'
        ]);
    }

    /**
     * Mark all notifications as read for the logged in user
     */
    public function markAllAsRead()
    {
        $userId = $this->getUserId();
        if (!$userId) {
            return $this->failUnauthorized('Not logged in');
        }

        $notificationModel = new NotificationModel();
        $notificationModel->where('user_id', $userId)
                          ->where('is_read', 0)
                          ->set(['is_read' => 1])
                          ->update();

        return $this->respond([
            'status' => 'success',
            'message' => 'All marked as read'
        ]);
    }
}

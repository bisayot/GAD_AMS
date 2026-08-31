<?php

namespace App\Controllers;

use App\Models\NewsIecModel;
use CodeIgniter\RESTful\ResourceController;

class ShareController extends ResourceController
{
    public function newsIec($id = null)
    {
        $model = new NewsIecModel();
        $post = $model->find($id);

        if (!$post) {
            // Redirect to frontend GAD corner if not found
            $frontendUrl = rtrim(getenv('FRONTEND_URL') ?: 'http://localhost:5173', '/');
            return redirect()->to($frontendUrl . '/gad-corner');
        }

        // Get the first image
        $imageUrl = '';
        if (!empty($post['image_path'])) {
            $images = json_decode($post['image_path'], true);
            if (is_array($images) && count($images) > 0) {
                // Construct absolute URL for the image
                $imageUrl = base_url('api/files/news-iec/' . $images[0]);
            }
        }

        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $currentUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $data = [
            'post' => $post,
            'imageUrl' => $imageUrl,
            'currentUrl' => $currentUrl,
            'frontendUrl' => rtrim(getenv('FRONTEND_URL') ?: 'http://localhost:5173', '/')
        ];

        return view('share_meta', $data);
    }
}

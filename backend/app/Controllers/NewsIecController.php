<?php

namespace App\Controllers;

use App\Models\NewsIecModel;
use CodeIgniter\RESTful\ResourceController;

class NewsIecController extends ResourceController
{
    protected $modelName = 'App\Models\NewsIecModel';
    protected $format    = 'json';

    public function index()
    {
        $category = $this->request->getVar('category');
        if ($category) {
            $data = $this->model->where('category', $category)->orderBy('created_at', 'desc')->findAll();
        } else {
            $data = $this->model->orderBy('created_at', 'desc')->findAll();
        }
        return $this->respond(['success' => true, 'data' => $data]);
    }

    public function show($id = null)
    {
        $data = $this->model->find($id);
        if ($data) {
            return $this->respond(['success' => true, 'data' => $data]);
        }
        return $this->failNotFound('Item not found');
    }

    public function create()
    {
        $rules = [
            'title'    => 'required|min_length[3]',
            'category' => 'required|in_list[News,IEC,Announcement]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $imagePaths = [];
        $files = $this->request->getFileMultiple('images');
        if ($files) {
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $uploadPath = FCPATH . 'uploads/news_iec';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $mime = $file->getMimeType();
                    $ext = strtolower($file->guessExtension() ?: $file->getExtension());
                    if (in_array($mime, $allowedMimes) && in_array($ext, $allowedExts)) {
                        $newName = $file->getRandomName();
                        $file->move($uploadPath, $newName);
                        $imagePaths[] = $newName;
                    }
                }
            }
        }
        
        $imagePathString = empty($imagePaths) ? null : json_encode($imagePaths);

        $data = [
            'title'        => $this->request->getPost('title'),
            'description'  => $this->request->getPost('description'),
            'tags'         => $this->request->getPost('tags'),
            'category'     => $this->request->getPost('category'),
            'image_path'   => $imagePathString,
            'published_by' => 1, // You could get this from auth session/token if implemented
        ];

        if ($this->model->insert($data)) {
            return $this->respondCreated(['success' => true, 'message' => 'Published successfully', 'id' => $this->model->insertID()]);
        }
        return $this->fail('Failed to publish');
    }

    public function update($id = null)
    {
        $item = $this->model->find($id);
        if (!$item) {
            return $this->failNotFound('Item not found');
        }

        $title = trim((string) $this->request->getPost('title'));
        $category = $this->request->getPost('category');
        if (mb_strlen($title) < 3 || mb_strlen($title) > 255) {
            return $this->failValidationErrors(['title' => 'Title must be between 3 and 255 characters.']);
        }
        if (!in_array($category, ['News', 'IEC', 'Announcement'], true)) {
            return $this->failValidationErrors(['category' => 'Invalid category.']);
        }

        $imagePath = $item['image_path'];
        $files = $this->request->getFileMultiple('images');
        $imagePaths = [];
        if ($files) {
            $uploadPath = FCPATH . 'uploads/news_iec';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $mime = $file->getMimeType();
                    $ext = strtolower($file->guessExtension() ?: $file->getExtension());
                    if (in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true)
                        && in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                        $newName = $file->getRandomName();
                        $file->move($uploadPath, $newName);
                        $imagePaths[] = $newName;
                    }
                }
            }
        }
        if ($imagePaths) {
            $this->deleteImages($imagePath);
            $imagePath = json_encode($imagePaths);
        }

        if (!$this->model->update($id, [
            'title' => $title,
            'description' => $this->request->getPost('description'),
            'tags' => $this->request->getPost('tags'),
            'category' => $category,
            'image_path' => $imagePath,
        ])) {
            return $this->fail('Failed to update post');
        }

        return $this->respond([
            'success' => true,
            'message' => 'Post updated successfully',
            'id'      => (int)$id,
        ]);
    }

    public function delete($id = null)
    {
        $item = $this->model->find($id);
        if (!$item) {
            return $this->failNotFound('Item not found');
        }

        if ($this->model->delete($id)) {
            $this->deleteImages($item['image_path'] ?? null);
            return $this->respondDeleted(['success' => true, 'message' => 'Deleted successfully']);
        }
        return $this->fail('Failed to delete');
    }

    private function deleteImages(?string $imagePath): void
    {
        if (!$imagePath) {
            return;
        }

        $images = json_decode($imagePath, true);
        if (!is_array($images)) {
            $images = [$imagePath];
        }

        $directories = [
            FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'news_iec',
            FCPATH . 'api' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'news_iec',
            WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'newsiec images',
        ];

        foreach ($images as $filename) {
            if (!is_string($filename) || $filename === '') {
                continue;
            }

            $safeFilename = basename(parse_url($filename, PHP_URL_PATH) ?: $filename);
            if ($safeFilename === '.' || $safeFilename === '..' || $safeFilename === '') {
                continue;
            }

            foreach ($directories as $directory) {
                $file = $directory . DIRECTORY_SEPARATOR . $safeFilename;
                if (is_file($file) && !unlink($file)) {
                    log_message('warning', 'Unable to delete News/IEC image: {file}', ['file' => $file]);
                }
            }
        }
    }
}

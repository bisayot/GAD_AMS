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
            'category' => 'required|in_list[News,IEC]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $imagePaths = [];
        $files = $this->request->getFileMultiple('images');
        if ($files) {
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $uploadPath = WRITEPATH . 'uploads/newsiec images';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    $file->move($uploadPath, $newName);
                    $imagePaths[] = $newName;
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

    public function delete($id = null)
    {
        $item = $this->model->find($id);
        if (!$item) {
            return $this->failNotFound('Item not found');
        }

        // Delete physical files
        if (!empty($item['image_path'])) {
            $images = json_decode($item['image_path'], true);
            if (is_array($images)) {
                $uploadPath = WRITEPATH . 'uploads/newsiec images/';
                foreach ($images as $filename) {
                    $filepath = $uploadPath . $filename;
                    if (is_file($filepath)) {
                        unlink($filepath);
                    }
                }
            }
        }

        if ($this->model->delete($id)) {
            return $this->respondDeleted(['success' => true, 'message' => 'Deleted successfully']);
        }
        return $this->fail('Failed to delete');
    }
}

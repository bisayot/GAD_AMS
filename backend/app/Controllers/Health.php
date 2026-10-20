<?php

namespace App\Controllers;

class Health extends BaseController
{
    public function index()
    {
        \Config\Database::connect()->query('SELECT 1');

        return $this->response->setJSON(['ok' => true]);
    }
}

<?php

namespace App\Controllers;

use App\Models\VenueModel;
use CodeIgniter\RESTful\ResourceController;

class VenueController extends ResourceController
{
    public function index()
    {
        $venueModel = new VenueModel();
        $venues = $venueModel->findAll();

        return $this->respond([
            'status' => 'success',
            'data'   => $venues,
        ]);
    }
}

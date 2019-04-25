<?php

namespace App\Http\Controllers\API;

use App\Repositories\AmenityRepository;

class AmenityController extends APIController
{
    public function __construct()
    {
        $this->repo           = new AmenityRepository();
        $this->acceptedParams = ['name', 'description'];
    }
}

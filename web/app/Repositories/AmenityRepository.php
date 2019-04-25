<?php

namespace App\Repositories;

use App\Models\Amenity;

class AmenityRepository extends Repository
{
    public function __construct()
    {
        $this->class      = Amenity::class;
        $this->limit      = 0;
        $this->createRule = [
            'name'        => 'required',
            'description' => 'nullable|string',
        ];
        $this->updateRule = [
            'name'        => 'filled',
            'description' => 'nullable|string',
        ];
        $this->searchable = ['name', 'description'];
        parent::__construct();
    }
}

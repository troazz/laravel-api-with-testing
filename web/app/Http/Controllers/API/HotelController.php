<?php

namespace App\Http\Controllers\API;

use App\Repositories\HotelRepository;

class HotelController extends APIController
{
    public function __construct()
    {
        $this->repo           = new HotelRepository();
        $this->acceptedParams = [
            'name',
            'description',
            'address',
            'longitude',
            'latitude',
            'commission_type',
            'commission_amount',
            'rooms',
        ];
    }
}

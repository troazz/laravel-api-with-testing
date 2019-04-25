<?php

namespace App\Http\Controllers\API;

use App\Repositories\CurrencyRepository;

class CurrencyController extends APIController
{
    public function __construct()
    {
        $this->repo           = new CurrencyRepository();
        $this->acceptedParams = ['name', 'symbol_location', 'symbol', 'code'];
    }
}

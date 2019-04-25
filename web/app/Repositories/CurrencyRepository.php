<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository extends Repository
{
    public function __construct()
    {
        $this->class      = Currency::class;
        $this->limit      = 0;
        $this->createRule = [
            'name'            => 'required',
            'symbol_location' => 'required|in:suffix,prefix',
            'symbol'          => 'required|string|max:5',
            'code'            => 'required|string|size:3',
        ];
        $this->updateRule = [
            'name'            => 'filled',
            'symbol_location' => 'filled|in:suffix,prefix',
            'symbol'          => 'filled|string|max:5',
            'code'            => 'filled|string|size:3',
        ];
        $this->searchable = ['name', 'code'];
        parent::__construct();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'symbol_location',
        'symbol',
        'code',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function rooms()
    {
        return $this->hasMany('App\Models\Room');
    }
}

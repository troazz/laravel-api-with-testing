<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Amenity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description',
    ];

    protected $hidden = [
        'deleted_at', 'pivot',
    ];

    public function rooms()
    {
        return $this->belongsToMany('App\Models\Room');
    }
}

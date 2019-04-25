<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use SoftDeletes;
    const COMMISSION_PERCENTAGE = 'percentage';
    const COMMISSION_EXACT      = 'exact';

    protected $fillable = [
        'name',
        'description',
        'address',
        'longitude',
        'latitude',
        'commission_type',
        'commission_amount',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function rooms()
    {
        return $this->hasMany('App\Models\Room');
    }
}

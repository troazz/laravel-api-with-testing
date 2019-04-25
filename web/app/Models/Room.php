<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'access_code',
        'max_occupancy',
        'net_rate',
        'currency_id',
        'hotel_id',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $appends = ['sell_rate'];

    public function hotel()
    {
        return $this->belongsTo('App\Models\Hotel');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

    public function amenities()
    {
        return $this->belongsToMany('App\Models\Amenity');
    }

    public function getCommissionRateAttribute()
    {
        return $this->hotel->commission_type == Hotel::COMMISSION_EXACT ? $this->hotel->commission_amount : ($this->net_rate * $this->hotel->commission_amount / 100);
    }

    public function getSellRateAttribute()
    {
        return $this->net_rate + $this->commission_rate;
    }
}

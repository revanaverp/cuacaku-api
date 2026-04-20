<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    protected $fillable = [
        'city_id',
        'temperature',
        'condition',
        'humidity',
        'wind_speed',
    ];

    // Relasi ke city
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}

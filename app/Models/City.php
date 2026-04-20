<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name',
        'province',
        'country',
        'latitude',
        'longitude'
    ];

    // Relasi ke weather
    public function weathers()
    {
        return $this->hasMany(Weather::class);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            [
                'name' => 'Surabaya',
                'province' => 'Jawa Timur',
                'country' => 'Indonesia',
                'latitude' => -7.2575,
                'longitude' => 112.7521,
            ],
            [
                'name' => 'Malang',
                'province' => 'Jawa Timur',
                'country' => 'Indonesia',
                'latitude' => -7.9666,
                'longitude' => 112.6326,
            ],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}

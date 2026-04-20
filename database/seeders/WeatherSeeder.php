<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Weather;
use App\Models\City;

class WeatherSeeder extends Seeder
{
    public function run(): void
    {
        $surabaya = City::where('name', 'Surabaya')->first();
        $malang   = City::where('name', 'Malang')->first();

        $weathers = [
            [
                'city_id' => $surabaya->id,
                'temperature' => 32,
                'condition' => 'Cerah',
                'humidity' => 70,
                'wind_speed' => 10,
            ],
            [
                'city_id' => $malang->id,
                'temperature' => 24,
                'condition' => 'Hujan',
                'humidity' => 85,
                'wind_speed' => 5,
            ],
        ];

        foreach ($weathers as $weather) {
            Weather::create($weather);
        }
    }
}

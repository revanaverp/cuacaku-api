<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'city' => $this->city?->name,
            'temperature' => $this->temperature,
            'condition' => $this->condition,
            'humidity' => $this->humidity,
            'wind_speed' => $this->wind_speed,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}

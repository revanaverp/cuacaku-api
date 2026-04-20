<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\CityController;

Route::apiResource('weathers', WeatherController::class);
Route::apiResource('cities', CityController::class);

// API luar
Route::get('/external-weather', [WeatherController::class, 'externalWeather']);
Route::get('/fetch-weather', [WeatherController::class, 'fetchAndStore']);

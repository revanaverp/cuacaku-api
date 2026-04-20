<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Weather;
use App\Models\City;
use App\Http\Resources\WeatherResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    // GET /api/weathers
    public function index()
    {
        $weathers = Weather::with('city')->latest()->get();

        return WeatherResource::collection($weathers);
    }

    // GET /api/weathers/{id}
    public function show(string $id)
    {
        $weather = Weather::with('city')->find($id);

        if (!$weather) {
            return response()->json([
                'success' => false,
                'message' => 'Data cuaca tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new WeatherResource($weather)
        ]);
    }

    // POST /api/weathers
    public function store(Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'temperature' => 'required|numeric',
            'condition' => 'required|string',
            'humidity' => 'required|integer|min:0|max:100',
            'wind_speed' => 'required|numeric',
        ]);

        $weather = Weather::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data cuaca berhasil ditambahkan.',
            'data' => new WeatherResource($weather),
        ], 201);
    }

    // PUT /api/weathers/{id}
    public function update(Request $request, string $id)
    {
        $weather = Weather::find($id);

        if (!$weather) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'city_id' => 'sometimes|exists:cities,id',
            'temperature' => 'sometimes|numeric',
            'condition' => 'sometimes|string',
            'humidity' => 'sometimes|integer|min:0|max:100',
            'wind_speed' => 'sometimes|numeric',
        ]);

        $weather->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate.',
            'data' => new WeatherResource($weather),
        ]);
    }

    // DELETE /api/weathers/{id}
    public function destroy(string $id)
    {
        $weather = Weather::find($id);

        if (!$weather) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        $weather->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus.'
        ]);
    }

    // 🌤️ GET API LUAR (TANPA SIMPAN)
    // GET /api/external-weather?city=Jakarta
    public function externalWeather(Request $request)
    {
        $cityName = $request->get('city', 'Surabaya');
        $apiKey = env('OPENWEATHER_API_KEY');

        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $cityName,
            'appid' => $apiKey,
            'units' => 'metric'
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari API luar'
            ], 500);
        }

        $data = $response->json();

        if (!isset($data['name'], $data['main'], $data['weather'], $data['wind'])) {
            return response()->json([
                'success' => false,
                'message' => 'Format data API tidak valid'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'city' => $data['name'],
                'temperature' => $data['main']['temp'],
                'condition' => $data['weather'][0]['main'],
                'humidity' => $data['main']['humidity'],
                'wind_speed' => $data['wind']['speed'],
            ]
        ]);
    }

    // 🔥 GET API LUAR + SIMPAN DATABASE
    // GET /api/fetch-weather?city=Jakarta
    public function fetchAndStore(Request $request)
    {
        $cityName = $request->get('city', 'Surabaya');
        $apiKey = env('OPENWEATHER_API_KEY');

        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $cityName,
            'appid' => $apiKey,
            'units' => 'metric'
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari API luar'
            ], 500);
        }

        $data = $response->json();

        if (!isset($data['name'], $data['main'], $data['weather'], $data['wind'])) {
            return response()->json([
                'success' => false,
                'message' => 'Format data API tidak valid'
            ], 500);
        }

        // 🔍 cari / buat city otomatis
        $city = City::firstOrCreate([
            'name' => $data['name']
        ]);

        // simpan weather
        $weather = Weather::create([
            'city_id' => $city->id,
            'temperature' => $data['main']['temp'],
            'condition' => $data['weather'][0]['main'],
            'humidity' => $data['main']['humidity'],
            'wind_speed' => $data['wind']['speed'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diambil & disimpan',
            'data' => new WeatherResource($weather)
        ]);
    }
}

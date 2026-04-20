<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Http\Resources\CityResource;
use Illuminate\Http\Request;

class CityController extends Controller
{
    // GET /api/cities
    public function index(Request $request)
    {
        $query = City::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return CityResource::collection($query->get());
    }

    // GET /api/cities/{id}
    public function show(string $id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json([
                'success' => false,
                'message' => 'Kota tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new CityResource($city)
        ]);
    }

    // POST
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'province' => 'nullable|string',
            'country' => 'required|string',
        ]);

        $city = City::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kota berhasil ditambahkan.',
            'data' => new CityResource($city),
        ], 201);
    }

    // PUT
    public function update(Request $request, string $id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json([
                'success' => false,
                'message' => 'Kota tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'string',
            'province' => 'nullable|string',
            'country' => 'string',
        ]);

        $city->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kota berhasil diupdate.',
            'data' => new CityResource($city),
        ]);
    }

    // DELETE
    public function destroy(string $id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json([
                'success' => false,
                'message' => 'Kota tidak ditemukan.'
            ], 404);
        }

        $city->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kota berhasil dihapus.'
        ]);
    }
}

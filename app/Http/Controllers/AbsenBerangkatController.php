<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsenBerangkat;

class AbsenBerangkatController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'nullable|integer',
            'nama' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'face' => 'nullable|string',
            'tanggal' => 'nullable|string',
            'jam' => 'nullable|string',
            'latitude' => 'nullable|decimal|integer',
            'longitude' => 'nullable|decimal|integer',
            'lokasi' => 'nullable|string',
            'uuid' => 'nullable|char|string',
        ]);

        $absenBerangkat = AbsenBerangkat::create($request->all());

        return response()->json($absenBerangkat, 201);
    }

    // Untuk mengambil seluruh data AbsenBerangkatS
    public function index()
    {
        $absenBerangkat = AbsenBerangkat::all();
        return response()->json($absenBerangkat);
    }

    // Untuk mengambil data berdasarkan ID
    public function show($id)
    {
        $absenBerangkat = AbsenBerangkat::find($id);
        if (!$absenBerangkat) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json($absenBerangkat);
    }

    // Untuk mengupdate data
    public function update(Request $request, $id)
    {
        $absenBerangkat = AbsenBerangkat::find($id);
        if (!$absenBerangkat) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $absenBerangkat->update($request->all());

        return response()->json($absenBerangkat);
    }

    // Untuk menghapus data
    public function destroy($id)
    {
        $absenBerangkat = AbsenBerangkat::find($id);
        if (!$absenBerangkat) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $absenBerangkat->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }
}

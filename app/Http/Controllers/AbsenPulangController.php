<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsenPulang;

class AbsenPulangController extends Controller
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

        $absenPulang = AbsenPulang::create($request->all());

        return response()->json($absenPulang, 201);
    }

    // Untuk mengambil seluruh data AbsenPulangS
    public function index()
    {
        $absenPulang = AbsenPulang::all();
        return response()->json($absenPulang);
    }

    // Untuk mengambil data berdasarkan ID
    public function show($id)
    {
        $absenPulang = AbsenPulang::find($id);
        if (!$absenPulang) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json($absenPulang);
    }

    // Untuk mengupdate data
    public function update(Request $request, $id)
    {
        $absenPulang = AbsenPulang::find($id);
        if (!$absenPulang) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $absenPulang->update($request->all());

        return response()->json($absenPulang);
    }

    // Untuk menghapus data
    public function destroy($id)
    {
        $absenPulang = AbsenPulang::find($id);
        if (!$absenPulang) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $absenPulang->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }
}

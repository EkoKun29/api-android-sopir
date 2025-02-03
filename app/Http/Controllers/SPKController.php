<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SPK;

class SPKController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'nullable|string',
            'nama_sales' => 'nullable|string',
            'tanggal_muat' => 'nullable|string',
            'hari_jam_keberangkatan' => 'nullable|string',
            'hari_Jam_kepulangan' => 'nullable|string',
            'sopir' => 'nullable|string',
            'rute' => 'nullable|string',
        ]);

        $spk = SPK::create($request->all());

        return response()->json($spk, 201);
    }

    // Untuk mengambil seluruh data SPKS
    public function index()
    {
        $spk = SPK::all();
        return response()->json($spk);
    }

    // Untuk mengambil data berdasarkan ID
    public function show($id)
    {
        $spk = SPK::find($id);
        if (!$spk) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json($spk);
    }

    // Untuk mengupdate data
    public function update(Request $request, $id)
    {
        $spk = SPK::find($id);
        if (!$spk) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $spk->update($request->all());

        return response()->json($spk);
    }

    // Untuk menghapus data
    public function destroy($id)
    {
        $spk = SPK::find($id);
        if (!$spk) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $spk->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }
}

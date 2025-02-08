<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SPK;

class SPKController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'tanggal' => 'nullable|date',
        'nama_sales' => 'nullable|string|max:255',
        'tanggal_muat' => 'nullable|date',
        'hari_jam_keberangkatan' => 'nullable|string|max:255',
        'hari_Jam_kepulangan' => 'nullable|string|max:255',
        'sopir' => 'nullable|string|max:255',
        'rute' => 'nullable|string|max:255',
        'dropper' => 'nullable|string|max:255',
        'keterangan' => 'nullable|string|max:255',
    ]);

    $spk = SPK::create([
        'id_user' => auth()->id(), // Ambil ID user dari token login
        'tanggal' => $request->tanggal,
        'nama_sales' => $request->nama_sales,
        'tanggal_muat' => $request->tanggal_muat,
        'hari_jam_keberangkatan' => $request->hari_jam_keberangkatan,
        'hari_Jam_kepulangan' => $request->hari_jam_kepulangan,
        'sopir' => $request->sopir,
        'rute' => $request->rute,
        'dropper' => $request->dropper,
        'keterangan' => $request->keterangan,
    ]);

    return response()->json($spk, 201);
}


    // Untuk mengambil seluruh data SPKS
    public function index()
    {
        $spk = SPK::orderBy('created_at', 'desc')->get();
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

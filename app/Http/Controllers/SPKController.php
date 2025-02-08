<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SPK;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SPKController extends Controller
{
   
    public function store(Request $request)
{
    $user = Auth::user(); 
    if (!$user) {
        return response()->json(['error' => 'User tidak ditemukan'], 401);
    }

    $spk = SPK::create([
        'id_user' => $user->id, // Ambil ID user dari token login
        'tanggal' => Carbon::now()->format('d/m/Y'),
        'nama_sales' => $request->nama_sales,
        'tanggal_muat' => Carbon::parse($request->tanggal_muat)->format('d/m/Y'),
        'hari_jam_keberangkatan' => $request->hari_jam_keberangkatan,
        'hari_Jam_kepulangan' => $request->hari_Jam_kepulangan,
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

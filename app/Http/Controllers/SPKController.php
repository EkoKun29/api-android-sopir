<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SPK;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class SPKController extends Controller
{
   
    public function store(Request $request)
{
    try {
        Log::info('Request diterima:', $request->all());

        $user = Auth::user(); 
        if (!$user) {
            Log::error('User tidak ditemukan');
            return response()->json(['error' => 'User tidak ditemukan'], 401);
        }

        Log::info('User ditemukan: ' . $user->id);

        // Cek apakah tanggal_muat null atau tidak valid
        if (!$request->tanggal_muat) {
            Log::error('tanggal_muat null!');
            return response()->json(['error' => 'Tanggal muat tidak boleh kosong'], 400);
        }

        $tanggal_muat = null;
        try {
            $tanggal_muat = Carbon::createFromFormat('j/n/Y', $request->tanggal_muat)->format('d/m/Y');
        } catch (\Exception $e) {
            Log::error('Format tanggal_muat salah: ' . $request->tanggal_muat);
            return response()->json(['error' => 'Format tanggal salah. Harus dalam bentuk j/n/Y'], 400);
        }

        Log::info('Tanggal muat setelah parsing: ' . $tanggal_muat);

        $spk = SPK::create([
            'id_user' => $user->id,
            'tanggal' => Carbon::now()->format('d/m/Y'),
            'nama_sales' => $request->nama_sales,
            'tanggal_muat' => $tanggal_muat,
            'hari_jam_keberangkatan' => $request->hari_jam_keberangkatan,
            'hari_Jam_kepulangan' => $request->hari_Jam_kepulangan,
            'sopir' => $request->sopir,
            'rute' => $request->rute,
            'dropper' => $request->dropper,
            'keterangan' => $request->keterangan,
        ]);

        Log::info('SPK berhasil dibuat:', $spk->toArray());

        return response()->json($spk, 201);
    } catch (\Exception $e) {
        Log::error('Gagal menyimpan SPK: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
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

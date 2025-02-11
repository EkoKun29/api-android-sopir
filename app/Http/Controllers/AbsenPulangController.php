<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsenPulang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AbsenPulangController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user = Auth::user(); 
            if (!$user) {
                return response()->json(['error' => 'User tidak ditemukan'], 401);
            }
    
            Log::info('Data diterima dari Android:', $request->all());
    
            $absenPulang = AbsenPulang::create([
                'id_user' => $user->id,
                'nama' => $request->nama,
                'jabatan' => $user->role,
                'face' => $request->face,
                'tanggal' => Carbon::now()->format('d/m/Y'),
                'jam' => Carbon::now()->format('H:i:s'),
                'latitude' => $request->latitude, 
                'longitude' => $request->longitude,
                'lokasi' => $request->lokasi,
                'uuid' => $request->uuid,
            ]);
    
            return response()->json($absenPulang, 201);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan SPK: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan data', 'message' => $e->getMessage()], 500);
        }
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

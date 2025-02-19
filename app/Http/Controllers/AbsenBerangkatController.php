<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsenBerangkat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AbsenBerangkatController extends Controller
{
    public function store(Request $request)
{
    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 401);
        }

        // Log data yang diterima
        Log::info('Data diterima dari Android:', $request->all());

        // Ambil data gambar dari request
        $imageData = $request->face;
        if (!$imageData) {
            return response()->json(['error' => 'Gambar tidak ditemukan'], 400);
        }

        $uuid = $request->uuid ?? Str::uuid()->toString();
        $fileName = $uuid . '.jpeg'; 
        $imagePath = 'public/absensi/' . $fileName;

        // Hapus prefix base64
        $image = str_replace('data:image/jpeg;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        $decodedImage = base64_decode($image);

        if (!$decodedImage) {
            return response()->json(['error' => 'Format gambar tidak valid'], 400);
        }

        // Simpan gambar ke storage
        Storage::put($imagePath, $decodedImage);

        // Simpan data ke database
        $absenBerangkat = AbsenBerangkat::create([
            'id_user' => $user->id,
            'nama' => $user->name,
            'jabatan' => $user->role,
            'face' => $fileName, // Simpan nama file gambar
            'tanggal' => Carbon::now()->format('d/m/Y'),
            'jam' => Carbon::now()->format('H:i:s'),
            'latitude' => $request->latitude, 
            'longitude' => $request->longitude,
            'lokasi' => $request->lokasi,
            'uuid' => $uuid, // Gunakan UUID dari request atau generate baru
        ]);

        return response()->json(['message' => 'Absen berhasil', 'data' => $absenBerangkat], 201);
    } catch (\Exception $e) {
        Log::error('Gagal menyimpan absen: ' . $e->getMessage());
        return response()->json(['error' => 'Gagal menyimpan data', 'message' => $e->getMessage()], 500);
    }
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

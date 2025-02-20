<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsenPulang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AbsenPulangController extends Controller
{
    public function store(Request $request)
{
    try {
        $user = Auth::user();

        if (!$user || $user->role !== 'user') {
            return response()->json(['message' => 'Akses ditolak!'], 403);
        }

        // Validasi input request
        $validated = $request->validate([
            'face' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi' => 'required|string',
        ]);

        // Ambil data gambar dari request
        $imageData = $validated['face'];
        $uuid = $request->uuid ?? Str::uuid()->toString();
        $fileName = $uuid . '.jpeg'; 
        $imagePath = 'public/absen-pulang/' . $fileName;

        // Decode Base64 Image
        $image = str_replace('data:image/jpeg;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        $decodedImage = base64_decode($image);

        if (!$decodedImage) {
            return response()->json(['error' => 'Format gambar tidak valid'], 400);
        }

        // Simpan gambar ke storage
        Storage::put($imagePath, $decodedImage);

        // Simpan data ke database
        $absenPulang = AbsenPulang::create([
            'id_user' => $user->id,
            'nama' => $user->name,
            'jabatan' => $user->role,
            'face' => $fileName, 
            'tanggal' => Carbon::now()->format('d/m/Y'),
            'jam' => Carbon::now()->format('H:i:s'),
            'latitude' => $validated['latitude'], 
            'longitude' => $validated['longitude'],
            'lokasi' => $validated['lokasi'],
            'uuid' => $uuid, 
        ]);

        return response()->json(['message' => 'Absen berhasil', 'data' => $absenPulang], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['error' => 'Validasi gagal', 'message' => $e->errors()], 422);
    } catch (\Exception $e) {
        Log::error('Gagal menyimpan absen: ' . $e->getMessage());
        return response()->json(['error' => 'Gagal menyimpan data', 'message' => $e->getMessage()], 500);
    }
}

public function __construct()
{
    $this->middleware('auth:sanctum');
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

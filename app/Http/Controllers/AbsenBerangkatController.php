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

        if (!$user || $user->role !== 'user') {
            return response()->json(['message' => 'Akses ditolak!'], 403);
        }

        // Validasi input request
        $validated = $request->validate([
            'face' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi' => 'required|string',
            'ket' => 'required|string',
        ]);

        // Ambil data gambar dari request
        $imageData = $validated['face'];
        $uuid = $request->uuid ?? Str::uuid()->toString();
        $fileName = $uuid . '.jpeg'; 
        $imagePath = 'public/absensi/' . $fileName;

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
        $absenBerangkat = AbsenBerangkat::create([
            'id_user' => $user->id,
            'nama' => $user->name,
            'jabatan' => $user->role,
            'face' => $fileName, 
            'tanggal' => Carbon::now()->format('d/m/Y'),
            'jam' => Carbon::now()->format('H:i:s'),
            'latitude' => $validated['latitude'], 
            'longitude' => $validated['longitude'],
            'lokasi' => $validated['lokasi'],
            'ket' => $validated['ket'],
            'uuid' => $uuid, 
        ]);

        return response()->json(['message' => 'Absen berhasil', 'data' => $absenBerangkat], 201);

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


    // Untuk mengambil seluruh data AbsenBerangkatS
    public function index(Request $request)
{

    $user = $request->user(); 

    if ($user->role === 'admin') {
        $absenBerangkat = AbsenBerangkat::orderBy('created_at', 'desc')->get();
    } else {
        $absenBerangkat = AbsenBerangkat::where('id_user', $user->id)->orderBy('created_at', 'desc')->get();
    }

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

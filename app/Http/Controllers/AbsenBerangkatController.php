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

            $imageData = $request->face; 

            // Simpan data ke database
            // $absenBerangkat = AbsenBerangkat::create([
            //     'id_user' => $user->id,
            //     'nama' => $user->name,
            //     'jabatan' => $user->role,
            //     $fileName = $request->uuid . '.jpeg', 
            //     $imagePath = 'public/' . $fileName, 

            //     $image = str_replace('data:image/jpeg;base64,', '', $imageData),
            //     $image = str_replace(' ', '+', $image),
            //     Storage::put($imagePath, base64_decode($image)),
            //     'face' => $fileName, 
            //     'tanggal' => Carbon::now()->format('d/m/Y'),
            //     'jam' => Carbon::now()->format('H:i:s'),
            //     'latitude' => $request->latitude, 
            //     'longitude' => $request->longitude,
            //     'lokasi' => $request->lokasi,
            //     'uuid' => Str::uuid(),
            // ]);

            $absenBerangkat = new AbsenBerangkat();
            $absenBerangkat ->id_user = Auth::user()->id;
            $absenBerangkat->uuid =Str::uuid();
            $absenBerangkat->nama = Auth::user()->name;
            $absenBerangkat->jabatan = Auth::user()->role;
            $absenBerangkat->tanggal = Carbon::now()->format('d/m/Y');
            $absenBerangkat->jam = Carbon::now()->format('H:i:s');
            $absenBerangkat->latitude = $request->latitude;
            $absenBerangkat->longitude = $request->longitude;
            $absenBerangkat->lokasi = $request->lokasi;
            $fileName = $absenBerangkat->uuid . '.jpeg'; // Nama file
            $imagePath = 'public/' . $fileName; // Path untuk disimpan di storage
            $image = str_replace('data:image/png;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);

            // Simpan gambar di storage/app/public
            Storage::put($imagePath, base64_decode($image)); // Simpan gambar

            // Simpan nama file ke database
            $absenBerangkat->face = $fileName;
            $absenBerangkat->save();


            return response()->json($absenBerangkat, 201);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan SPK: ' . $e->getMessage());
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

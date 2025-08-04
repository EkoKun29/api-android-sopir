<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SPKJember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;


class SPKJemberController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user = Auth::user(); 
            if (!$user) {
                return response()->json(['error' => 'User tidak ditemukan'], 401);
            }

            // Log data yang dikirim
            Log::info('Data diterima dari Android:', $request->all());

            // Format hari dan jam keberangkatan
            $hari_jam_keberangkatan = $this->formatHariJam($request->tanggal_keberangkatan, $request->jam_keberangkatan);
            $hari_jam_kepulangan = $this->formatHariJam($request->tanggal_kepulangan, $request->jam_kepulangan);

            $spkJember = SPKJember::create([
                'id_user' => $user->id,
                'tanggal' => Carbon::now()->format('d/m/Y'),
                'nama_sales' => $request->nama_sales,
                'tanggal_muat' => $request->tanggal_muat, 
                'hari_jam_keberangkatan' => $hari_jam_keberangkatan,
                'hari_Jam_kepulangan' => $hari_jam_kepulangan,
                'tanggal_keberangkatan' => $request->tanggal_keberangkatan,
                'jam_keberangkatan' => $request->jam_keberangkatan,
                'tanggal_kepulangan' => $request->tanggal_kepulangan,
                'jam_kepulangan' => $request->jam_kepulangan,
                'sopir' => $request->sopir,
                'rute' => $request->rute,
                'dropper' => $request->dropper,
                'keterangan' => $request->keterangan,
            ]);

            return response()->json($spkJember, 201);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan SPKJember: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan data', 'message' => $e->getMessage()], 500);
        }
    }


    private function formatHariJam($tanggal, $jam)
{
    if (!$tanggal || !$jam) {
        return null; // Jika tidak ada tanggal atau jam, kembalikan null
    }

    try {
        // Pastikan tanggal dalam format "DD/MM/YYYY" dan jam dalam format "HH:mm"
        $dateTime = Carbon::createFromFormat('d/m/Y H:i', "$tanggal $jam")->locale('id');

        return $dateTime->translatedFormat('l H:i') . ' ' . $this->getWaktuHari($dateTime->format('H:i'));
    } catch (\Exception $e) {
        Log::error("Gagal memformat tanggal/jam: " . $e->getMessage());
        return null; // Jika gagal parsing, kembalikan null
    }
}



    // Fungsi menentukan waktu pagi, siang, atau malam
    private function getWaktuHari($time)
    {
        $hour = (int) explode(':', $time)[0]; // Ambil jam dari format HH:mm:ss atau HH:mm
        if ($hour >= 1 && $hour < 12) {
            return 'pagi';
        } elseif ($hour >= 12 && $hour < 14) {
            return 'siang';
        } elseif ($hour >= 14 && $hour < 17) {
            return 'sore';
        } else {
            return 'malam';
        }
    }

    // Untuk mengambil seluruh data SPKJemberS
    public function index(Request $request)
{

    $user = $request->user();
    $today = Carbon::today();



    if ($user->role === 'operasional jember') {
        $spkJember = SPKJember::orderBy('created_at', 'desc')->get();
    } else {
        $spkJember = SPKJember::where(function ($query) use ($user) {
                $query->where('dropper', $user->name)
                      ->orWhere('sopir', $user->name);
            })
            ->whereRaw("STR_TO_DATE(tanggal_muat, '%d/%m/%Y') >= ? AND STR_TO_DATE(tanggal_muat, '%d/%m/%Y') < ?", [
                $today->copy()->startOfMonth()->toDateString(),
                $today->copy()->addMonth()->startOfMonth()->toDateString()
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    
    }

    return response()->json($spkJember);
}


    // Untuk mengambil data berdasarkan ID
    public function show($id)
    {
        $spkJember = SPKJember::find($id);
        if (!$spkJember) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json($spkJember);
    }

    // Untuk mengupdate data
    public function update(Request $request, $id)
{
    try {
        $spkJember = SPKJember::find($id);
        if (!$spkJember) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        if ($request->has('tanggal_keberangkatan') || $request->has('jam_keberangkatan')) {
            $tanggal = $request->tanggal_keberangkatan ?? $spkJember->tanggal_keberangkatan;
            $jam = $request->jam_keberangkatan ?? $spkJember->jam_keberangkatan;

            if ($tanggal && $jam) {
            $request->merge([
                'hari_jam_keberangkatan' => $this->formatHariJam($tanggal, $jam)
            ]);
            }
        }

        // Handle kepulangan
        if ($request->has('tanggal_kepulangan') || $request->has('jam_kepulangan')) {
            $tanggal = $request->tanggal_kepulangan ?? $spkJember->tanggal_kepulangan;
            $jam = $request->jam_kepulangan ?? $spkJember->jam_kepulangan;

            if ($tanggal && $jam) {
                $request->merge([
                    'hari_Jam_kepulangan' => $this->formatHariJam($tanggal, $jam)
                ]);
            }
        }

        $spkJember->update($request->all());

        return response()->json($spkJember);
    } catch (\Exception $e) {
        Log::error('Gagal mengupdate SPKJember: ' . $e->getMessage());
        return response()->json(['error' => 'Gagal mengupdate data', 'message' => $e->getMessage()], 500);
    }
}


    // Untuk menghapus data
    public function destroy($id)
    {
        $spkJember = SPKJember::find($id);
        if (!$spkJember) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $spkJember->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }
}

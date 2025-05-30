<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SPK;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;


class SPKController extends Controller
{
   
//     public function store(Request $request)
// {
//     try {
//         $user = Auth::user(); 
//         if (!$user) {
//             return response()->json(['error' => 'User tidak ditemukan'], 401);
//         }

//         // Log data yang dikirim
//         Log::info('Data diterima dari Android:', $request->all());

//         $spk = SPK::create([
//             'id_user' => $user->id,
//             'tanggal' => Carbon::now()->format('d/m/Y'),
//             'nama_sales' => $request->nama_sales,
//             'tanggal_muat' => $request->tanggal_muat, 
//             'hari_jam_keberangkatan' => $request->hari_jam_keberangkatan,
//             'hari_Jam_kepulangan' => $request->hari_Jam_kepulangan,
//             'tanggal_keberangkatan' => $request->tanggal_keberangkatan,
//             'jam_keberangkatan' => $request->jam_keberangkatan,
//             'tanggal_kepulangan' => $request->tanggal_kepulangan,
//             'jam_kepulangan' => $request->jam_kepulangan,
//             'sopir' => $request->sopir,
//             'rute' => $request->rute,
//             'dropper' => $request->dropper,
//             'keterangan' => $request->keterangan,
//         ]);

//         return response()->json($spk, 201);
//     } catch (\Exception $e) {
//         Log::error('Gagal menyimpan SPK: ' . $e->getMessage());
//         return response()->json(['error' => 'Gagal menyimpan data', 'message' => $e->getMessage()], 500);
//     }
// }

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

            $spk = SPK::create([
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

            return response()->json($spk, 201);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan SPK: ' . $e->getMessage());
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

    // Untuk mengambil seluruh data SPKS
    public function index(Request $request)
{

    $user = $request->user();
    $today = Carbon::today();


    // $currentWeek = SPK::whereDate('tanggal_muat', '<=', $today)
    //                 ->orderBy('tanggal_muat', 'desc')
    //                 ->value('keterangan');

    // if (!$currentWeek) {
    // return response()->json([]); 
    // }

    if ($user->role === 'operasional') {
        $spk = SPK::orderBy('created_at', 'desc')->get();
    } else {
        $spk = SPK::where(function ($query) use ($user) {
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
    try {
        $spk = SPK::find($id);
        if (!$spk) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        // Perbarui hanya jika tanggal & jam keberangkatan/kepulangan diberikan
        if ($request->has(['tanggal_keberangkatan', 'jam_keberangkatan'])) {
            $request->merge([
                'hari_jam_keberangkatan' => $this->formatHariJam($request->tanggal_keberangkatan, $request->jam_keberangkatan)
            ]);
        }

        if ($request->has(['tanggal_kepulangan', 'jam_kepulangan'])) {
            $request->merge([
                'hari_jam_kepulangan' => $this->formatHariJam($request->tanggal_kepulangan, $request->jam_kepulangan)
            ]);
        }

        $spk->update($request->all());

        return response()->json($spk);
    } catch (\Exception $e) {
        Log::error('Gagal mengupdate SPK: ' . $e->getMessage());
        return response()->json(['error' => 'Gagal mengupdate data', 'message' => $e->getMessage()], 500);
    }
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

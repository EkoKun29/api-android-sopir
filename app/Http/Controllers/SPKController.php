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
   
    public function store(Request $request)
{
    try {
        $user = Auth::user(); 
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 401);
        }

        // Log data yang dikirim
        Log::info('Data diterima dari Android:', $request->all());

        $spk = SPK::create([
            'id_user' => $user->id,
            'tanggal' => Carbon::now()->format('d/m/Y'),
            'nama_sales' => $request->nama_sales,
            'tanggal_muat' => $request->tanggal_muat, 
            'hari_jam_keberangkatan' => $request->hari_jam_keberangkatan,
            'hari_Jam_kepulangan' => $request->hari_Jam_kepulangan,
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

    // Untuk mengambil seluruh data SPKS
    public function index(Request $request)
{

    $user = $request->user();
    $today = Carbon::now();


    $currentWeek = SPK::whereDate('tanggal_muat', '<=', $today)
                    ->orderBy('tanggal_muat', 'desc')
                    ->value('keterangan');

    if (!$currentWeek) {
    return response()->json([]); 
    }

    if ($user->role === 'operasional') {
        $spk = SPK::orderBy('created_at', 'desc')->get();
    } else {
        $spk = SPK::where(function ($query) use ($user) {
            $query->where('dropper', $user->name)
                  ->orWhere('sopir', $user->name);
        })
        ->where('keterangan', $currentWeek)
        ->orderBy('tanggal_muat', 'asc')
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

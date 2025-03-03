<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AbsenBerangkat;
use App\Models\AbsenPulang;
use App\Models\SPK;
use Illuminate\Http\Request;

class ExportDataController extends Controller
{
    public function absen($startDate, $endDate){
        $rekap_berangkat = AbsenBerangkat::whereBetween('created_at', [$startDate, $endDate])->get();
        return response()->json($rekap_berangkat);
    }

    public function absenpulang($startDate, $endDate){
        $rekap_pulang = AbsenPulang::whereBetween('created_at', [$startDate, $endDate])->get();
        return response()->json($rekap_pulang);
    }

    public function spk($startDate, $endDate){
        $spk = SPK::whereBetween('created_at', [$startDate, $endDate])->get();
        return response()->json($spk);
    }
}

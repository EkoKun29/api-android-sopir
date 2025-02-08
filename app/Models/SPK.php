<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPK extends Model
{
    use HasFactory;

    protected $table = 's_p_k_s';

    protected $fillable = [
        'id_user',
        'tanggal',
        'nama_sales',
        'tanggal_muat',
        'hari_jam_keberangkatan',
        'hari_Jam_kepulangan',
        'sopir',
        'rute',
        'dropper',
        'keterangan',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}


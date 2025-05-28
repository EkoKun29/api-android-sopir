<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPKJember extends Model
{
    use HasFactory;
    protected $table = 's_p_k_jembers';
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
        'tanggal_keberangkatan',
        'jam_keberangkatan',
        'tanggal_kepulangan',
        'jam_kepulangan',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

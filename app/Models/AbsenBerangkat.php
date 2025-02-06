<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenBerangkat extends Model
{
    use HasFactory;
    protected $table = 'absen_berangkats';

    protected $fillable = [
        'id_user',
        'nama',
        'jabatan',
        'face',
        'tanggal',
        'jam',
        'latitude',
        'longitude',
        'lokasi',
        'uuid',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}



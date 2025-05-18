<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AbsenPulang extends Model
{
    use HasFactory;
    protected $table = 'absen_pulangs';

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
        'ket',
    ];

    public static function boot() {
        parent::boot();
        static::creating(function (AbsenPulang $absenPulang) {
            $absenPulang->uuid = Str::uuid()->toString();
        });
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public static function boot() {
        parent::boot();
        static::creating(function (AbsenBerangkat $absenBerangkat) {
            $absenBerangkat->uuid = Str::uuid()->toString();
        });
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}



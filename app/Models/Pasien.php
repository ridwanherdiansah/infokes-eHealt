<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    protected $table = 'pasiens';

    protected $fillable = [
        'no_ktp',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'rt',
        'rw',
        'desa',
        'kecamatan',
        'pekerjaan',
    ];
}

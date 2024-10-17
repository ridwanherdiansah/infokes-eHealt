<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    protected $table = 'pembayarans';

    protected $fillable = [
        'nomor_pembayaran',
        'nomor_rekam_medis',
        'tanggal_pembayaran',
        'biaya_konsultasi',
        'biaya_pemeriksaan',
        'biaya_obat',
        'biaya_administrasi',
        'total_pembayaran',
        'metode_pembayaran',
        'catatan',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kunjungan extends Model
{
    use HasFactory;
    protected $table = 'kunjungans';

    protected $fillable = [
        'nomor_rekam_medis',
        'no_ktp',
        'tanggal_kunjungan',
        'dokter_penanggung_jawab',
        'poli_departemen',
        'keperluan_kunjungan',
        'pembayaran',
        'catatan_tambahan',
    ];

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'poli_departemen', 'id');
    }
}

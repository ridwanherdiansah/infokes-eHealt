<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PembayaranExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;
    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Nomor Pembayaran',
            'Nomor Rekam Medis',
            'Tanggal Pembayaran',
            'Biaya Konsultasi',
            'Biaya Pemeriksaan',
            'Biaya Obat',
            'Total Pemabayaran',
            'Catatan',
        ];
    }
}

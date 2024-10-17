<?php

namespace App\Exports;

use App\Models\Kunjungan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KunjunganExport implements FromCollection, WithHeadings
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
            'No RM',
            'Nama',
            'No KTP',
            'Tanggal Kunjungan',
            'Dokter',
            'Poli',
            'Keperluan Kunjungan',
            'Pembayaran',
            'Catatan Tambahan',
            'Tanggal Dibuat',
        ];
    }
}

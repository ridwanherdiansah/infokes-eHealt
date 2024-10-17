<?php

namespace App\Exports;

use App\Models\Sub_menu;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PasienExport implements FromCollection, WithHeadings
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
            'Id Pasien',
            'NRM',
            'NO KTP',
            'Nama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Alamat',
            'RT',
            'RW',
            'Desa',
            'Kecamatan',
            'Pekerjaan',
        ];
    }
}

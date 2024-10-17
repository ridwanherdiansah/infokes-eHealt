<?php

namespace App\Exports;

use App\Models\Poli;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PoliExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $poli;
    public function __construct(Collection $poli)
    {
        $this->poli = $poli;
    }

    public function collection()
    {
        return $this->poli;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Nama',
            'Tanggal',
        ];
    }
}

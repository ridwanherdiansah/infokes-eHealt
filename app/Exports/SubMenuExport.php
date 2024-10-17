<?php

namespace App\Exports;

use App\Models\Sub_menu;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubMenuExport implements FromCollection, WithHeadings
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
            'Id Submenu',
            'Id Menu',
            'Nama Menu',
            'Nama Sub Menu',
            'Url',
            'Type Menu',
            'Icon',
            'Status',
            'Tanggal',
        ];
    }
}

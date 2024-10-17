<?php

namespace App\Exports;

use App\Models\Menu;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MenuExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $menu;
    public function __construct(Collection $menu)
    {
        $this->menu = $menu;
    }

    public function collection()
    {
        return $this->menu;
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

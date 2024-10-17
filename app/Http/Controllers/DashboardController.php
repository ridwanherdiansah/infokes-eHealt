<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Kunjungan;

class DashboardController extends Controller
{
    public function index(){
        $title = 'Dashboard';
        $type_menu = 'dashboard';

        $jumlah_pasien = Pasien::count();
        $jumlah_kunjungan_poli_umum = Kunjungan::whereHas('poli', function($query) {
            $query->where('poli', 'Poli Umum');
        })->count();

        $jumlah_kunjungan_poli_gigi = Kunjungan::whereHas('poli', function($query) {
        $query->where('poli', 'Poli Gigi');
        })->count();

        $jumlah_kunjungan_poli_mata = Kunjungan::whereHas('poli', function($query) {
            $query->where('poli', 'Poli Mata');
        })->count();
        
        return view(
        'Pages.admin.dashboard', 
        compact(
            'title', 
            'type_menu',
            'jumlah_pasien', 'jumlah_kunjungan_poli_umum', 'jumlah_kunjungan_poli_gigi', 'jumlah_kunjungan_poli_mata',
        ));
    }
}

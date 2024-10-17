<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SubMenuController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\PembayaranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login.index');
});

Route::get('/login', [LoginController::class, 'index'])->name('login.index');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');

// admin
Route::group(['middleware' => ['auth']],function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard.index');

    // Menu
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::get('/menu/filter', [MenuController::class, 'filter'])->name('menu.filter');
    Route::get('/menu/export', [MenuController::class, 'export'])->name('menu.export');
    Route::get('/menu/search', [MenuController::class, 'search'])->name('menu.search');
    Route::post('/menu/update/{id}', [MenuController::class, 'update'])->name('menu.update');
    Route::post('/menu/destroy/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');

    // Sub Menu
    Route::get('/subMenu', [SubMenuController::class, 'index'])->name('subMenu.index');
    Route::post('/subMenu', [SubMenuController::class, 'store'])->name('subMenu.store');
    Route::get('/subMenu/filter', [SubMenuController::class, 'filter'])->name('subMenu.filter');
    Route::get('/subMenu/export', [SubMenuController::class, 'export'])->name('subMenu.export');
    Route::get('/subMenu/search', [SubMenuController::class, 'search'])->name('subMenu.search');
    Route::post('/subMenu/update/{id}', [SubMenuController::class, 'update'])->name('subMenu.update');
    Route::post('/subMenu/destroy/{id}', [SubMenuController::class, 'destroy'])->name('subMenu.destroy');
    Route::get('/subMenu/status/{id}', [SubMenuController::class, 'status'])->name('subMenu.status');

    // Users
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/filter', [UsersController::class, 'filter'])->name('users.filter');
    Route::get('/users/export', [UsersController::class, 'export'])->name('users.export');
    Route::get('/users/search', [UsersController::class, 'search'])->name('users.search');
    Route::post('/users/update/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::post('/users/destroy/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/accessMenu/{id}', [UsersController::class, 'accessMenu'])->name('users.accessMenu');
    Route::get('/accessMenu/update/{role_id}/{menu_id}', [UsersController::class, 'accessMenuUpdate'])->name('users.accessMenuUpdate');

    // Poli
    Route::get('/poli', [PoliController::class, 'index'])->name('poli.index');
    Route::post('/poli', [PoliController::class, 'store'])->name('poli.store');
    Route::get('/poli/filter', [PoliController::class, 'filter'])->name('poli.filter');
    Route::get('/poli/export', [PoliController::class, 'export'])->name('poli.export');
    Route::get('/poli/search', [PoliController::class, 'search'])->name('poli.search');
    Route::post('/poli/update/{id}', [PoliController::class, 'update'])->name('poli.update');
    Route::post('/poli/destroy/{id}', [PoliController::class, 'destroy'])->name('poli.destroy');

    // Pasien
    Route::get('/pasien', [PasienController::class, 'index'])->name('pasien.index');
    Route::post('/pasien', [PasienController::class, 'store'])->name('pasien.store');
    Route::get('/pasien/filter', [PasienController::class, 'filter'])->name('pasien.filter');
    Route::get('/pasien/export', [PasienController::class, 'export'])->name('pasien.export');
    Route::get('/pasien/search', [PasienController::class, 'search'])->name('pasien.search');
    Route::post('/pasien/update/{id}', [PasienController::class, 'update'])->name('pasien.update');
    Route::post('/pasien/destroy/{id}', [PasienController::class, 'destroy'])->name('pasien.destroy');

    // Kunjungan
    Route::get('/kunjungan', [KunjunganController::class, 'index'])->name('kunjungan.index');
    Route::get('/kunjungan/create', [KunjunganController::class, 'create'])->name('kunjungan.create');
    Route::post('/kunjungan', [KunjunganController::class, 'store'])->name('kunjungan.store');
    Route::get('/kunjungan/filter', [KunjunganController::class, 'filter'])->name('kunjungan.filter');
    Route::get('/kunjungan/export', [KunjunganController::class, 'export'])->name('kunjungan.export');
    Route::get('/kunjungan/search', [KunjunganController::class, 'search'])->name('kunjungan.search');
    Route::post('/kunjungan/update/{id}', [KunjunganController::class, 'update'])->name('kunjungan.update');
    Route::post('/kunjungan/destroy/{id}', [KunjunganController::class, 'destroy'])->name('kunjungan.destroy');
    Route::post('/kunjungan/pembayaran/{id}', [KunjunganController::class, 'pembayaran'])->name('kunjungan.pemabayaran');
    Route::get('/kunjungan/updatePembayaran/{id}', [KunjunganController::class, 'updatePembayaran'])->name('kunjungan.updatePembayaran');

    // Pembayaran
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/filter', [PembayaranController::class, 'filter'])->name('pembayaran.filter');
    Route::get('/pembayaran/export', [PembayaranController::class, 'export'])->name('pembayaran.export');
    Route::get('/pembayaran/search', [PembayaranController::class, 'search'])->name('pembayaran.search');
    Route::post('/pembayaran/update/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');
    Route::post('/pembayaran/destroy/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');
});
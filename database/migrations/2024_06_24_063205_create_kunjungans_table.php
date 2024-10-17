<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_rekam_medis');
            $table->string('no_ktp');
            $table->dateTime('tanggal_kunjungan');
            $table->string('dokter_penanggung_jawab');
            $table->string('poli_departemen');
            $table->text('keperluan_kunjungan');
            $table->string('pembayaran');
            $table->text('catatan_tambahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};

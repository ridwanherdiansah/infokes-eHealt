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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pembayaran');
            $table->string('nomor_rekam_medis');
            $table->date('tanggal_pembayaran');
            $table->integer('biaya_konsultasi');
            $table->integer('biaya_pemeriksaan');
            $table->integer('biaya_obat');
            $table->integer('total_pembayaran');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};

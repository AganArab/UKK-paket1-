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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kendaraan');
            $table->unsignedBigInteger('id_area');
            $table->unsignedBigInteger('id_user')->nullable()->comment('User yang mencatat transaksi');
            $table->dateTime('waktu_masuk');
            $table->dateTime('waktu_keluar')->nullable();
            $table->decimal('total_bayar', 10, 0)->default(0);
            $table->enum('status', ['masuk', 'keluar'])->default('masuk');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_kendaraan')->references('id')->on('kendaraan')->onDelete('restrict');
            $table->foreign('id_area')->references('id')->on('area')->onDelete('restrict');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};

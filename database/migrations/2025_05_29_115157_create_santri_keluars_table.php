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
        Schema::create('santri_keluar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id')->unique()->unsigned();
            $table->string('keluar_karena', 30);
            $table->date('tanggal_keluar');
            $table->string('alasan_keluar')->nullable();
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('santri');
        });
        // Jenis Registrasi
        // 2 = Keluar
        // 3 = Lulus
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santri_keluar');
    }
};

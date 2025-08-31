<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anggota_kelas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id')->unsigned();
            $table->unsignedBigInteger('kelas_id')->unsigned();
            $table->enum('pendaftaran', ['1', '2', '3', '4', '5']);
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('santri')->onDelete('cascade');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');

            // Pendaftaran
            // 1 = Santri Baru
            // 2 = Pindahan
            // 3 = Naik Kelas
            // 4 = Lanjutan Semester
            // 5 = Mengulang
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_kelas');
    }
};

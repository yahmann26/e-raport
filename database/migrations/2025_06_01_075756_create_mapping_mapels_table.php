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
        Schema::create('mapping_mapel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mapel_id')->unsigned();
            $table->enum('kelompok', ['1', '2', '3']);
            $table->integer('nomor_urut');
            $table->timestamps();

            $table->foreign('mapel_id')->references('id')->on('mapel');
        });

        // Kelompok
        // 1 = Mapel Wajib
        // 2 = Pilihan
        // 3 = Muatan Lokal
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapping_mapel');
    }
};

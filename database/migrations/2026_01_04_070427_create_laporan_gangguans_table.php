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
        Schema::create('laporan_gangguans', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('instansi_id');
            $table->integer('jaringan_id');
            $table->integer('users_id');
            $table->integer('status_id');
            $table->string('judul', 100);
            $table->text('deskripsi');
            $table->timestamp('waktu_kejadian');
            $table->enum('prioritas', ['Rendah', 'Sedang', 'Tinggi']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_gangguans');
    }
};

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
        Schema::create('status_laporans', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->enum('nm_status', ['Baru', 'Diproses', 'Selesai', 'Ditolak']);
            $table->enum('warna', ['0', '1', '2','3','4']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_laporans');
    }
};

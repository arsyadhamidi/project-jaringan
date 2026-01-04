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
        Schema::create('jaringans', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('instansi_id');
            $table->string('tipe_jaringan', 100);
            $table->string('provider', 100);
            $table->string('ip_address', 100);
            $table->string('bandwidth', 100);
            $table->enum('status', ['Online', 'Offline']);
            $table->string('keterangan', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jaringans');
    }
};

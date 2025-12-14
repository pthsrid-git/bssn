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
        Schema::create('penanggung_jawab_mengisi_kinerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('unit_kerja_id')->constrained('unit_kerja')->onDelete('cascade');
            $table->foreignId('pko_id')->constrained('pengelola_kinerja_organisasi')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('unit_kerja_id');
            $table->index('pko_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penanggung_jawab_mengisi_kinerja');
    }
};

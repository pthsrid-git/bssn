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
        Schema::create('perkin_nko', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_kerja_id')->constrained('unit_kerja')->onDelete('cascade');
            $table->integer('tahun');
            $table->string('perkin_file')->nullable();
            $table->string('nko_tw1_file')->nullable();
            $table->string('nko_tw2_file')->nullable();
            $table->string('nko_tw3_file')->nullable();
            $table->string('nko_tw4_file')->nullable();
            $table->string('nko_tahunan_file')->nullable();
            $table->timestamps();
            
            $table->index('unit_kerja_id');
            $table->index('tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perkin_nko');
    }
};

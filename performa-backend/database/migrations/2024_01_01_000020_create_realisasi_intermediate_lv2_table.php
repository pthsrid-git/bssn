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
        Schema::create('realisasi_intermediate_lv2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerjemahan_intermediate_lv2_id')->constrained('penerjemahan_intermediate_lv2')->onDelete('cascade');
            $table->foreignId('indicator_id')->constrained('penerjemahan_intermediate_lv2_indicators')->onDelete('cascade');
            $table->integer('tahun');
            $table->decimal('target_tahunan', 10, 2)->nullable();
            $table->decimal('realisasi_tahunan', 10, 2)->nullable();
            $table->decimal('target_tw1', 10, 2)->nullable();
            $table->decimal('target_tw2', 10, 2)->nullable();
            $table->decimal('target_tw3', 10, 2)->nullable();
            $table->decimal('target_tw4', 10, 2)->nullable();
            $table->decimal('realisasi_tw1', 10, 2)->nullable();
            $table->decimal('realisasi_tw2', 10, 2)->nullable();
            $table->decimal('realisasi_tw3', 10, 2)->nullable();
            $table->decimal('realisasi_tw4', 10, 2)->nullable();
            $table->timestamps();
            
            $table->index('penerjemahan_intermediate_lv2_id', 'idx_realisasi_int_lv2_penerjemahan_int_lv2_id');
            $table->index('indicator_id', 'idx_realisasi_int_lv2_indicator_id');
            $table->index('tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi_intermediate_lv2');
    }
};

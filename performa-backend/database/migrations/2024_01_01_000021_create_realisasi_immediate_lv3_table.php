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
        Schema::create('realisasi_immediate_lv3', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerjemahan_immediate_lv3_id')->constrained('penerjemahan_immediate_lv3')->onDelete('cascade');
            $table->foreignId('indicator_id')->constrained('penerjemahan_immediate_lv3_indicators')->onDelete('cascade');
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
            
            $table->index('penerjemahan_immediate_lv3_id', 'idx_realisasi_imm_lv3_penerjemahan_imm_lv3_id');
            $table->index('indicator_id', 'idx_realisasi_imm_lv3_indicator_id');
            $table->index('tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi_immediate_lv3');
    }
};

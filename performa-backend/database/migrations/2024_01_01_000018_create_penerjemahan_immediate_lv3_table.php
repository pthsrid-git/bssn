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
        Schema::create('penerjemahan_immediate_lv3', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immediate_outcome_lv3_id')->constrained('immediate_outcomes_lv3')->onDelete('cascade');
            $table->foreignId('unit_kerja_id')->constrained('unit_kerja')->onDelete('cascade');
            $table->integer('tahun');
            $table->timestamps();
            
            $table->index('immediate_outcome_lv3_id', 'idx_penerjemahan_imm_lv3_imm_outcome_lv3_id');
            $table->index('unit_kerja_id', 'idx_penerjemahan_imm_lv3_unit_kerja_id');
            $table->index('tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerjemahan_immediate_lv3');
    }
};

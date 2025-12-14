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
        Schema::create('penerjemahan_intermediate_lv2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intermediate_outcome_lv2_id')->constrained('intermediate_outcomes_lv2')->onDelete('cascade');
            $table->foreignId('unit_kerja_id')->constrained('unit_kerja')->onDelete('cascade');
            $table->integer('tahun');
            $table->timestamps();
            
            $table->index('intermediate_outcome_lv2_id', 'idx_penerjemahan_int_lv2_int_outcome_lv2_id');
            $table->index('unit_kerja_id', 'idx_penerjemahan_int_lv2_unit_kerja_id');
            $table->index('tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerjemahan_intermediate_lv2');
    }
};

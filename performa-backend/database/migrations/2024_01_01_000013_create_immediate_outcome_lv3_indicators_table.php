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
        Schema::create('immediate_outcome_lv3_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immediate_outcome_lv3_id')->constrained('immediate_outcomes_lv3')->onDelete('cascade');
            $table->text('iksk');
            $table->timestamps();
            
            $table->index('immediate_outcome_lv3_id', 'idx_imm_outcome_lv3_indicators_imm_outcome_lv3_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('immediate_outcome_lv3_indicators');
    }
};

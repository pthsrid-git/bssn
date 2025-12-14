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
        Schema::create('intermediate_outcome_lv1_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intermediate_outcome_lv1_id')->constrained('intermediate_outcomes_lv1')->onDelete('cascade');
            $table->text('indikator_kinerja');
            $table->timestamps();
            
            $table->index('intermediate_outcome_lv1_id', 'idx_int_outcome_lv1_indicators_int_outcome_lv1_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intermediate_outcome_lv1_indicators');
    }
};

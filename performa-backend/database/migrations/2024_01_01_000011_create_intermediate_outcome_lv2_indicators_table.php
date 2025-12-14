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
        Schema::create('intermediate_outcome_lv2_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intermediate_outcome_lv2_id')->constrained('intermediate_outcomes_lv2')->onDelete('cascade');
            $table->text('iksp');
            $table->timestamps();
            
            $table->index('intermediate_outcome_lv2_id', 'idx_int_outcome_lv2_indicators_int_outcome_lv2_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intermediate_outcome_lv2_indicators');
    }
};

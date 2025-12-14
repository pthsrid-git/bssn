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
        Schema::create('final_outcome_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('final_outcome_id')->constrained('final_outcomes')->onDelete('cascade');
            $table->text('indikator_kinerja');
            $table->timestamps();
            
            $table->index('final_outcome_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_outcome_indicators');
    }
};

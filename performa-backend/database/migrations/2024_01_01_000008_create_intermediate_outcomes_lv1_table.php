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
        Schema::create('intermediate_outcomes_lv1', function (Blueprint $table) {
            $table->id();
            $table->foreignId('final_outcome_id')->constrained('final_outcomes')->onDelete('cascade');
            $table->string('kode_uo', 10);
            $table->string('kode_int_o_lv1', 20);
            $table->text('sasaran');
            $table->timestamps();
            
            $table->index('final_outcome_id');
            $table->index('kode_int_o_lv1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intermediate_outcomes_lv1');
    }
};

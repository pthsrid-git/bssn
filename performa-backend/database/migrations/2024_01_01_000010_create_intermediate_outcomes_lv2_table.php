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
        Schema::create('intermediate_outcomes_lv2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intermediate_outcome_lv1_id')->constrained('intermediate_outcomes_lv1')->onDelete('cascade');
            $table->string('kode_uo', 10);
            $table->string('kode_int_o_lv1', 20);
            $table->string('kode_int_o_lv2', 30);
            $table->text('sasaran_program');
            $table->timestamps();
            
            $table->index('intermediate_outcome_lv1_id', 'idx_int_outcomes_lv2_int_outcome_lv1_id');
            $table->index('kode_int_o_lv2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intermediate_outcomes_lv2');
    }
};

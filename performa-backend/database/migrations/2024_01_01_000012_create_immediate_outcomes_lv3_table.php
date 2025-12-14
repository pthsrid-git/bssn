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
        Schema::create('immediate_outcomes_lv3', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intermediate_outcome_lv2_id')->constrained('intermediate_outcomes_lv2')->onDelete('cascade');
            $table->string('kode_uo', 10);
            $table->string('kode_int_o_lv1', 20);
            $table->string('kode_int_o_lv2', 30);
            $table->string('kode_imm_o_lv3', 40);
            $table->text('sasaran_kegiatan');
            $table->timestamps();
            
            $table->index('intermediate_outcome_lv2_id', 'idx_imm_outcomes_lv3_int_outcome_lv2_id');
            $table->index('kode_imm_o_lv3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('immediate_outcomes_lv3');
    }
};

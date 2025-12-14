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
        Schema::create('outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immediate_outcome_lv3_id')->constrained('immediate_outcomes_lv3')->onDelete('cascade');
            $table->string('kode_uo', 10);
            $table->string('kode_int_o_lv1', 20);
            $table->string('kode_int_o_lv2', 30);
            $table->string('kode_imm_o_lv3', 40);
            $table->string('kode_io', 50);
            $table->text('deskripsi');
            $table->timestamps();
            
            $table->index('immediate_outcome_lv3_id', 'idx_outputs_imm_outcome_lv3_id');
            $table->index('kode_io');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outputs');
    }
};

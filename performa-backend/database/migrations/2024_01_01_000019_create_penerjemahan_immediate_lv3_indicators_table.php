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
        Schema::create('penerjemahan_immediate_lv3_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerjemahan_immediate_lv3_id')->constrained('penerjemahan_immediate_lv3')->onDelete('cascade');
            $table->text('iksk');
            $table->timestamps();
            
            $table->index('penerjemahan_immediate_lv3_id', 'idx_penerjemahan_imm_lv3_ind_penerjemahan_imm_lv3_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerjemahan_immediate_lv3_indicators');
    }
};

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
        Schema::create('penerjemahan_intermediate_lv2_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerjemahan_intermediate_lv2_id')->constrained('penerjemahan_intermediate_lv2')->onDelete('cascade');
            $table->text('iksp');
            $table->timestamps();
            
            $table->index('penerjemahan_intermediate_lv2_id', 'idx_penerjemahan_int_lv2_ind_penerjemahan_int_lv2_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerjemahan_intermediate_lv2_indicators');
    }
};

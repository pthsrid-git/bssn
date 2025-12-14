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
        Schema::create('output_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('output_id')->constrained('outputs')->onDelete('cascade');
            $table->text('indikator_output');
            $table->timestamps();
            
            $table->index('output_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('output_indicators');
    }
};

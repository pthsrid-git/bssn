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
        Schema::create('final_outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode')->onDelete('cascade');
            $table->string('kode_uo', 10);
            $table->text('sasaran');
            $table->timestamps();
            
            $table->index('periode_id');
            $table->index('kode_uo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_outcomes');
    }
};

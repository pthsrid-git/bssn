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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role'); // admin-eperforma, pko, pmk
            $table->string('unit_kerja')->nullable(); // untuk PKO
            $table->string('unit_kerja_pko')->nullable(); // untuk PMK
            $table->timestamps();

            // Unique constraint: satu user hanya bisa punya satu role yang sama
            $table->unique(['user_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Required fields for frontend
            $table->string('guid')->unique();
            $table->string('uuid')->unique();
            $table->string('name');
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('fpid');
            $table->string('nip')->unique();
            $table->string('pangkat')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('password');
            $table->rememberToken();

            // Role & Hierarchy
            $table->string('role'); // pko, pmk, ka-unit, admin
            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('kode_unit_organisasi')->nullable();

            $table->timestamps();

            $table->index('guid');
            $table->index('uuid');
            $table->index('email');
            $table->index('nip');
            $table->index('role');
            $table->index('parent_id');
            $table->index('kode_unit_organisasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

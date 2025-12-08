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
            $table->string('guid')->unique();
            $table->string('fpid');
            $table->string('nama_pegawai');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nip_nrp');
            $table->string('pangkat_golongan')->nullable();
            $table->string('golongan')->nullable();
            $table->string('kode_peta_jabatan')->nullable();
            $table->string('nama_jabatan')->nullable();
            $table->string('kelas')->nullable();
            $table->string('kode_unit_organisasi')->nullable();
            $table->string('nama_unit_organisasi')->nullable();
            $table->text('nama_lengkap_peta_jabatan')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Indexes untuk performance (PostgreSQL)
            $table->index('email');
            $table->index('guid');
            $table->index('nip_nrp');
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

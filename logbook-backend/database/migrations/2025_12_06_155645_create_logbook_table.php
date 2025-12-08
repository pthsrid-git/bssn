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
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('rencana_hasil_kinerja_skp');
            $table->string('indikator_hasil_rencana_kerja');
            $table->text('aktivitas_kegiatan_harian');
            $table->text('keterangan')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('status', 20)->default('Draft');
            $table->text('catatan_katim')->nullable();
            $table->text('catatan_atasan')->nullable();
            $table->timestamps();

            // Indexes untuk performance
            $table->index('user_id');
            $table->index('tanggal');
            $table->index('status');
            $table->index(['user_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};

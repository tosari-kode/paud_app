<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('guru_paud', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lembaga_id');
            $table->string('nama', 100);
            $table->string('nuptk', 30)->unique();
            $table->string('pendidikan_terakhir', 50);
            $table->enum('status_guru', ['PNS', 'Honorer', 'Kontrak']);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->boolean('sertifikasi')->default(0)->comment('1=Sudah, 0=Belum');
            $table->string('pelatihan_terakhir', 255)->nullable();
            $table->year('tahun_masuk')->nullable();
            $table->timestamps();

            // Relasi ke lembaga_paud
            $table->foreign('lembaga_id')
                ->references('id')
                ->on('lembaga_paud')
                ->onDelete('cascade');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_paud');
    }
};

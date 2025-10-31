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
         Schema::create('kecamatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kecamatan', 100);
            $table->string('kode', 20)->unique();
            $table->timestamps();
        });

        Schema::create('desa', function (Blueprint $table) {
            $table->id();
            $table->string('nama_desa', 100);
            $table->string('kode', 20)->unique();
            $table->unsignedBigInteger('kecamatan_id');
            $table->timestamps();

            $table->foreign('kecamatan_id')
                ->references('id')
                ->on('kecamatan')
                ->onDelete('cascade');
        });
        Schema::create('lembaga_paud', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lembaga', 150);
            $table->string('npsn', 20)->unique();
            $table->enum('jenis_lembaga', ['TK', 'KB', 'TPA', 'SPS']);
            $table->enum('status_izin', ['izin', 'proses', 'belum'])->default('belum');
            $table->enum('akreditasi', ['A', 'B', 'C', 'Belum'])->default('Belum');
            $table->text('alamat');
            $table->unsignedBigInteger('desa_id');
            $table->string('kepala_lembaga', 100);
            $table->string('no_hp', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('latitude', 50)->nullable();
            $table->string('longitude', 50)->nullable();
            $table->string('foto', 255)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=aktif, 0=nonaktif');
            $table->timestamps();

            $table->foreign('desa_id')
                ->references('id')
                ->on('desa')
                ->onDelete('cascade');
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['super_admin', 'lembaga'])->default('lembaga');
            $table->unsignedBigInteger('lembaga_id')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=aktif, 0=nonaktif');
            $table->rememberToken();
            $table->timestamps();

            // Relasi opsional (kalau tabel lembaga & desa sudah ada)
            $table->foreign('lembaga_id')->references('id')->on('lembaga_paud')->onDelete('set null');

        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatan');
        Schema::dropIfExists('desa');
        Schema::dropIfExists('lembaga_paud');
        Schema::dropIfExists('users');
    }
};

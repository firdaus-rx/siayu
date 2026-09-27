<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();

            // IDENTITAS PENGADU
            $table->string('nama_lengkap');
            $table->string('nik', 32)->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 32)->nullable();
            $table->string('email', 255)->nullable();

            // MATERI PENGADUAN
            $table->string('materi_pengaduan')->nullable();
            $table->text('deskripsi_pengaduan');

            // LAMPIRAN
            $table->string('lampiran_ktp')->nullable();
            $table->string('lampiran_lainnya')->nullable();

            // STATUS TINDAK LANJUT
            $table->enum('status', ['pending', 'proses', 'selesai', 'tidak_dapat_ditindaklanjuti'])->default('pending');
            $table->text('catatan_admin')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};

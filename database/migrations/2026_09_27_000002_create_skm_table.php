<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skm', function (Blueprint $table) {
            $table->id();

            // DEMOGRAFI
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('usia', 16);
            $table->string('pendidikan', 16);
            $table->string('pekerjaan', 32);
            $table->string('jenis_layanan', 64);

            // 9 PERTANYAAN KEPUASAN (skala 1-4)
            $table->unsignedTinyInteger('p1_kesesuaian_persyaratan');
            $table->unsignedTinyInteger('p2_kemudahan_prosedur');
            $table->unsignedTinyInteger('p3_jadwal_waktu');
            $table->unsignedTinyInteger('p4_tarif_biaya');
            $table->unsignedTinyInteger('p5_produk_hasil');
            $table->unsignedTinyInteger('p6_kompetensi_petugas');
            $table->unsignedTinyInteger('p7_perilaku_petugas');
            $table->unsignedTinyInteger('p8_sarana_prasarana');
            $table->unsignedTinyInteger('p9_penanganan_pengaduan');

            // SARAN
            $table->text('saran_perbaikan')->nullable();

            $table->timestamps();

            $table->index('created_at');
            $table->index('jenis_layanan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skm');
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skm extends Model
{
    protected $table = 'skm';

    protected $fillable = [
        'jenis_kelamin',
        'usia',
        'pendidikan',
        'pekerjaan',
        'jenis_layanan',
        'p1_kesesuaian_persyaratan',
        'p2_kemudahan_prosedur',
        'p3_jadwal_waktu',
        'p4_tarif_biaya',
        'p5_produk_hasil',
        'p6_kompetensi_petugas',
        'p7_perilaku_petugas',
        'p8_sarana_prasarana',
        'p9_penanganan_pengaduan',
        'saran_perbaikan',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    protected $appends = ['skor_rata_rata'];

    public function getSkorRataRataAttribute(): float
    {
        $total = $this->p1_kesesuaian_persyaratan
            + $this->p2_kemudahan_prosedur
            + $this->p3_jadwal_waktu
            + $this->p4_tarif_biaya
            + $this->p5_produk_hasil
            + $this->p6_kompetensi_petugas
            + $this->p7_perilaku_petugas
            + $this->p8_sarana_prasarana
            + $this->p9_penanganan_pengaduan;

        return round($total / 9, 2);
    }

    public const PERTANYAAN_LABELS = [
        'p1_kesesuaian_persyaratan' => 'Kesesuaian persyaratan dengan jenis pelayanan',
        'p2_kemudahan_prosedur' => 'Kemudahan prosedur pelayanan',
        'p3_jadwal_waktu' => 'Jadwal dan waktu yang ditetapkan petugas',
        'p4_tarif_biaya' => 'Tarif/biaya yang dikenakan',
        'p5_produk_hasil' => 'Produk hasil pelayanan',
        'p6_kompetensi_petugas' => 'Kompetensi petugas',
        'p7_perilaku_petugas' => 'Perilaku petugas (kesopanan & keramahan)',
        'p8_sarana_prasarana' => 'Sarana dan prasarana pelayanan',
        'p9_penanganan_pengaduan' => 'Penanganan pengaduan, saran dan masukan',
    ];

    public const JAWABAN_LABELS = [
        1 => 'Sangat Tidak Puas',
        2 => 'Tidak Puas',
        3 => 'Puas',
        4 => 'Sangat Puas',
    ];

    public function skorRataRata(): float
    {
        $total = $this->p1_kesesuaian_persyaratan
            + $this->p2_kemudahan_prosedur
            + $this->p3_jadwal_waktu
            + $this->p4_tarif_biaya
            + $this->p5_produk_hasil
            + $this->p6_kompetensi_petugas
            + $this->p7_perilaku_petugas
            + $this->p8_sarana_prasarana
            + $this->p9_penanganan_pengaduan;

        return round($total / 9, 2);
    }
}

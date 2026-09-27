<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'alamat',
        'no_telepon',
        'email',
        'materi_pengaduan',
        'deskripsi_pengaduan',
        'lampiran_ktp',
        'lampiran_lainnya',
        'status',
        'catatan_admin',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public const STATUS_LABELS = [
        'pending' => 'Pending',
        'proses' => 'Proses',
        'selesai' => 'Selesai',
        'tidak_dapat_ditindaklanjuti' => 'Tidak Dapat Ditindaklanjuti',
    ];

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }
}

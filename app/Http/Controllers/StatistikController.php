<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Skm;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StatistikController extends Controller
{
    public function index()
    {
        // Statistik Pengaduan
        $pengaduanStats = [
            'total' => Pengaduan::count(),
            'pending' => Pengaduan::where('status', 'pending')->count(),
            'proses' => Pengaduan::where('status', 'proses')->count(),
            'selesai' => Pengaduan::where('status', 'selesai')->count(),
            'tidak_dapat_ditindaklanjuti' => Pengaduan::where('status', 'tidak_dapat_ditindaklanjuti')->count(),
        ];

        // Statistik SKM
        $skmTotal = Skm::count();
        $skmStats = [
            'total' => $skmTotal,
            'rata_rata' => $skmTotal > 0 ? round(Skm::query()->selectRaw('
                ROUND(AVG(
                    (p1_kesesuaian_persyaratan + p2_kemudahan_prosedur + p3_jadwal_waktu +
                     p4_tarif_biaya + p5_produk_hasil + p6_kompetensi_petugas +
                     p7_perilaku_petugas + p8_sarana_prasarana + p9_penanganan_pengaduan) / 9
                ), 2) as rata_rata
            ')->value('rata_rata'), 2) : 0,
            'distribusi_jawaban' => [
                'sangat_tidak_puas' => Skm::where('p1_kesesuaian_persyaratan', 1)->count(),
                'tidak_puas' => Skm::where('p1_kesesuaian_persyaratan', 2)->count(),
                'puas' => Skm::where('p1_kesesuaian_persyaratan', 3)->count(),
                'sangat_puas' => Skm::where('p1_kesesuaian_persyaratan', 4)->count(),
            ],
            'jenis_layanan' => Skm::query()
                ->selectRaw('jenis_layanan')
                ->selectRaw('count(*) as total')
                ->groupBy('jenis_layanan')
                ->orderByDesc('total')
                ->get(),
        ];

        return Inertia::render('Welcome/Index', [
            'pengaduanStats' => $pengaduanStats,
            'skmStats' => $skmStats,
        ]);
    }
}

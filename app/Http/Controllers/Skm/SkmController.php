<?php

namespace App\Http\Controllers\Skm;

use App\Http\Controllers\Controller;
use App\Models\Skm;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SkmController extends Controller
{
    public function create()
    {
        return Inertia::render('Skm/Form', [
            'flash' => [
                'success' => session('success'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'usia' => 'required|string|in:<=18,19-25,26-35,36-45,46-55,>=56',
            'pendidikan' => 'required|string|in:SD,SMP,SMA,D3,S1,S2,S3',
            'pekerjaan' => 'required|string|max:32',
            'jenis_layanan' => 'required|string|max:64',
            'p1_kesesuaian_persyaratan' => 'required|integer|between:1,4',
            'p2_kemudahan_prosedur' => 'required|integer|between:1,4',
            'p3_jadwal_waktu' => 'required|integer|between:1,4',
            'p4_tarif_biaya' => 'required|integer|between:1,4',
            'p5_produk_hasil' => 'required|integer|between:1,4',
            'p6_kompetensi_petugas' => 'required|integer|between:1,4',
            'p7_perilaku_petugas' => 'required|integer|between:1,4',
            'p8_sarana_prasarana' => 'required|integer|between:1,4',
            'p9_penanganan_pengaduan' => 'required|integer|between:1,4',
            'saran_perbaikan' => 'nullable|string',
        ]);

        Skm::create($validated);

        toast_success('Terima kasih atas partisipasi Anda dalam Survei Kepuasan Masyarakat.');

        return redirect()->route('skm.create');
    }
}

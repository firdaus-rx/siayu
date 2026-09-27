<?php

namespace App\Http\Controllers\Skm;

use App\Http\Controllers\Controller;
use App\Models\Skm;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminSkmController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'jenis_layanan' => 'nullable|string|max:64',
            'per_page' => 'nullable|integer|in:10,15,25,50',
        ]);

        $filters = [
            'search' => $validated['search'] ?? '',
            'jenis_layanan' => $validated['jenis_layanan'] ?? '',
            'per_page' => (int) ($validated['per_page'] ?? 15),
        ];

        $query = Skm::query();

        if ($filters['jenis_layanan'] !== '') {
            $query->where('jenis_layanan', $filters['jenis_layanan']);
        }

        $skm = $query->orderByDesc('id')
            ->paginate($filters['per_page'])
            ->withQueryString()
            ->onEachSide(1);

        // Append skor_rata_rata ke setiap item
        $skm->getCollection()->transform(function ($item) {
            $item->skor_rata_rata = $item->skorRataRata();
            return $item;
        });

        // Statistik
        $stats = $this->hitungStatistik();

        // Distribusi jenis layanan
        $layananStats = Skm::query()
            ->selectRaw('jenis_layanan')
            ->selectRaw('count(*) as total')
            ->groupBy('jenis_layanan')
            ->orderByDesc('total')
            ->get();

        // Distribusi demografi
        $demografi = [
            'jenis_kelamin' => Skm::query()->selectRaw('jenis_kelamin')->selectRaw('count(*) as total')->groupBy('jenis_kelamin')->get(),
            'usia' => Skm::query()->selectRaw('usia')->selectRaw('count(*) as total')->groupBy('usia')->get(),
            'pendidikan' => Skm::query()->selectRaw('pendidikan')->selectRaw('count(*) as total')->groupBy('pendidikan')->get(),
            'pekerjaan' => Skm::query()->selectRaw('pekerjaan')->selectRaw('count(*) as total')->groupBy('pekerjaan')->orderByDesc('total')->get(),
        ];

        return Inertia::render('Skm/Admin/Index', [
            'skm' => $skm,
            'filters' => $filters,
            'stats' => $stats,
            'layananStats' => $layananStats,
            'demografi' => $demografi,
            'flash' => [
                'success' => session('success'),
            ],
        ]);
    }

    public function show(Skm $skm)
    {
        return Inertia::render('Skm/Admin/Show', [
            'skm' => $skm,
        ]);
    }

    public function destroy(Skm $skm)
    {
        $skm->delete();

        toast_success('Data SKM berhasil dihapus.');

        return redirect()->route('admin.skm.index');
    }

    private function hitungStatistik(): array
    {
        $total = Skm::count();

        if ($total === 0) {
            return [
                'total' => 0,
                'rata_rata' => 0,
                'distribusi_jawaban' => [],
                'rata_rata_per_pertanyaan' => [],
            ];
        }

        // Rata-rata semua jawaban
        $rataRata = Skm::query()->selectRaw('
            ROUND(AVG(
                (p1_kesesuaian_persyaratan + p2_kemudahan_prosedur + p3_jadwal_waktu +
                 p4_tarif_biaya + p5_produk_hasil + p6_kompetensi_petugas +
                 p7_perilaku_petugas + p8_sarana_prasarana + p9_penanganan_pengaduan) / 9
            ), 2) as rata_rata
        ')->value('rata_rata');

        // Distribusi jawaban per pertanyaan
        $distribusi = [];
        $rataPerPertanyaan = [];

        foreach (Skm::PERTANYAAN_LABELS as $field => $label) {
            $distribusi[$field] = Skm::query()
                ->selectRaw("{$field} as jawaban")
                ->selectRaw('count(*) as total')
                ->groupBy($field)
                ->orderBy($field)
                ->get()
                ->mapWithKeys(fn($item) => [$item->jawaban => $item->total]);

            $rataPerPertanyaan[$field] = round(Skm::query()->avg($field), 2);
        }

        return [
            'total' => $total,
            'rata_rata' => $rataRata,
            'distribusi_jawaban' => $distribusi,
            'rata_rata_per_pertanyaan' => $rataPerPertanyaan,
        ];
    }
}

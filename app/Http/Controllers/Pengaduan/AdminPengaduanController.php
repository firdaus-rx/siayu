<?php

namespace App\Http\Controllers\Pengaduan;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminPengaduanController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:pending,proses,selesai,tidak_dapat_ditindaklanjuti',
            'per_page' => 'nullable|integer|in:10,15,25,50',
        ]);

        $filters = [
            'search' => $validated['search'] ?? '',
            'status' => $validated['status'] ?? '',
            'per_page' => (int) ($validated['per_page'] ?? 15),
        ];

        $query = Pengaduan::query();

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('materi_pengaduan', 'like', "%{$search}%")
                    ->orWhere('deskripsi_pengaduan', 'like', "%{$search}%");
            });
        }

        if ($filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        $pengaduan = $query->orderByDesc('id')
            ->paginate($filters['per_page'])
            ->withQueryString()
            ->onEachSide(1);

        // Statistik
        $stats = [
            'total' => Pengaduan::count(),
            'pending' => Pengaduan::where('status', 'pending')->count(),
            'proses' => Pengaduan::where('status', 'proses')->count(),
            'selesai' => Pengaduan::where('status', 'selesai')->count(),
            'tidak_dapat_ditindaklanjuti' => Pengaduan::where('status', 'tidak_dapat_ditindaklanjuti')->count(),
        ];

        // Statistik per jenis materi
        $materiStats = Pengaduan::query()
            ->selectRaw('COALESCE(NULLIF(materi_pengaduan, ""), "Tidak Ditentukan") as materi')
            ->selectRaw('count(*) as total')
            ->groupBy('materi')
            ->orderByDesc('total')
            ->get();

        return Inertia::render('Pengaduan/Admin/Index', [
            'pengaduan' => $pengaduan,
            'filters' => $filters,
            'stats' => $stats,
            'materiStats' => $materiStats,
            'flash' => [
                'success' => session('success'),
            ],
        ]);
    }

    public function show(Pengaduan $pengaduan)
    {
        return Inertia::render('Pengaduan/Admin/Show', [
            'pengaduan' => $pengaduan,
            'flash' => [
                'success' => session('success'),
            ],
        ]);
    }

    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,proses,selesai,tidak_dapat_ditindaklanjuti',
            'catatan_admin' => 'nullable|string',
        ]);

        $pengaduan->update([
            'status' => $validated['status'],
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        toast_success('Status pengaduan berhasil diperbarui.');

        return redirect()->route('admin.pengaduan.show', $pengaduan);
    }

    public function downloadLampiran(Pengaduan $pengaduan, string $jenis)
    {
        $field = $jenis === 'ktp' ? 'lampiran_ktp' : 'lampiran_lainnya';

        if (!$pengaduan->$field) {
            abort(404, 'Lampiran tidak ditemukan.');
        }

        $path = Storage::disk('local')->path($pengaduan->$field);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan di server.');
        }

        return response()->download($path);
    }

    public function previewLampiran(Pengaduan $pengaduan, string $jenis)
    {
        $field = $jenis === 'ktp' ? 'lampiran_ktp' : 'lampiran_lainnya';

        if (!$pengaduan->$field) {
            abort(404, 'Lampiran tidak ditemukan.');
        }

        $path = Storage::disk('local')->path($pengaduan->$field);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan di server.');
        }

        $mimeType = mime_content_type($path);

        return response()->file($path, [
            'Content-Type' => $mimeType,
        ]);
    }

    public function destroy(Pengaduan $pengaduan)
    {
        // Hapus file lampiran
        if ($pengaduan->lampiran_ktp) {
            Storage::disk('local')->delete($pengaduan->lampiran_ktp);
        }
        if ($pengaduan->lampiran_lainnya) {
            Storage::disk('local')->delete($pengaduan->lampiran_lainnya);
        }

        $pengaduan->delete();

        toast_success('Data pengaduan berhasil dihapus.');

        return redirect()->route('admin.pengaduan.index');
    }
}

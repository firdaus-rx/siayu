<?php

namespace App\Http\Controllers\Pengaduan;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PengaduanController extends Controller
{
    public function create()
    {
        return Inertia::render('Pengaduan/Form', [
            'flash' => [
                'success' => session('success'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'nullable|string|max:32',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:32',
            'email' => 'nullable|email|max:255',
            'materi_pengaduan' => 'nullable|string|max:255',
            'deskripsi_pengaduan' => 'required|string',
            'lampiran_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'lampiran_lainnya' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Upload KTP
        if ($request->hasFile('lampiran_ktp')) {
            $file = $request->file('lampiran_ktp');
            $filename = 'ktp_' . time() . '_' . $file->hashName();
            $path = $file->storeAs('pengaduan', $filename, 'local');
            $validated['lampiran_ktp'] = $path;
        }

        // Upload lampiran lainnya
        if ($request->hasFile('lampiran_lainnya')) {
            $file = $request->file('lampiran_lainnya');
            $filename = 'lainnya_' . time() . '_' . $file->hashName();
            $path = $file->storeAs('pengaduan', $filename, 'local');
            $validated['lampiran_lainnya'] = $path;
        }

        Pengaduan::create($validated);

        toast_success('Pengaduan Anda telah berhasil dikirim. Terima kasih atas laporan Anda.');

        return redirect()->route('pengaduan.create');
    }
}

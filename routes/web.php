<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pengaduan\PengaduanController;
use App\Http\Controllers\Pengaduan\AdminPengaduanController;
use App\Http\Controllers\Pengawasan\ImportPdfController;
use App\Http\Controllers\Pengawasan\PengawasanController;
use App\Http\Controllers\SanksiAdministratif\ImportPdfController as SanksiImportPdfController;
use App\Http\Controllers\SanksiAdministratif\RekapController;
use App\Http\Controllers\SanksiAdministratif\SanksiAdministratifController;
use App\Http\Controllers\SanksiAdministratif\Sp1Controller;
use App\Http\Controllers\Skm\SkmController;
use App\Http\Controllers\Skm\AdminSkmController;
use App\Http\Controllers\StatistikController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $pengaduanStats = [
        'total' => \App\Models\Pengaduan::count(),
        'pending' => \App\Models\Pengaduan::where('status', 'pending')->count(),
        'proses' => \App\Models\Pengaduan::where('status', 'proses')->count(),
        'selesai' => \App\Models\Pengaduan::where('status', 'selesai')->count(),
        'tidak_dapat_ditindaklanjuti' => \App\Models\Pengaduan::where('status', 'tidak_dapat_ditindaklanjuti')->count(),
    ];

    $skmTotal = \App\Models\Skm::count();
    $skmStats = [
        'total' => $skmTotal,
        'rata_rata' => $skmTotal > 0 ? round(\App\Models\Skm::query()->selectRaw('
            ROUND(AVG(
                (p1_kesesuaian_persyaratan + p2_kemudahan_prosedur + p3_jadwal_waktu +
                 p4_tarif_biaya + p5_produk_hasil + p6_kompetensi_petugas +
                 p7_perilaku_petugas + p8_sarana_prasarana + p9_penanganan_pengaduan) / 9
            ), 2) as rata_rata
        ')->value('rata_rata'), 2) : 0,
    ];

    return view('guest.welcome', compact('pengaduanStats', 'skmStats'));
})->name('welcome');

// Auth — hanya login, tanpa register
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::prefix('pengawasan')->group(function () {
        Route::get('/import', [ImportPdfController::class, 'index'])
            ->name('pengawasan.import');
        Route::post('/import', [ImportPdfController::class, 'store'])
            ->name('pengawasan.import.store');
        Route::post('/import/simpan', [ImportPdfController::class, 'simpanHasil'])
            ->name('pengawasan.import.simpan');
        Route::get('/import/clear', [ImportPdfController::class, 'clearSession'])
            ->name('pengawasan.import.clear');
    });

    Route::resource('/pengawasan', PengawasanController::class);

    // Sanksi Administratif - pola sama seperti pengawasan, key OCR: no, nib, alamat nested, penanaman_modal, skala_usaha
    Route::prefix('sanksi-administratif')->group(function () {
        Route::get('/import', [SanksiImportPdfController::class, 'index'])
            ->name('sanksi-administratif.import');
        Route::post('/import', [SanksiImportPdfController::class, 'store'])
            ->name('sanksi-administratif.import.store');
        Route::post('/import/simpan', [SanksiImportPdfController::class, 'simpanHasil'])
            ->name('sanksi-administratif.import.simpan');
        Route::get('/import/clear', [SanksiImportPdfController::class, 'clearSession'])
            ->name('sanksi-administratif.import.clear');

        // Cetak SP1 per data / massal dari template sp1.blade.php
        Route::get('/sp1/cetak', [Sp1Controller::class, 'show'])
            ->name('sanksi-administratif.sp1.massal');
        Route::get('/{sanksiAdministratif}/sp1', [Sp1Controller::class, 'show'])
            ->name('sanksi-administratif.sp1.show');
        Route::get('/{sanksiAdministratif}/sp1/print', [Sp1Controller::class, 'print'])
            ->name('sanksi-administratif.sp1.print');

        // Rekap tabel No | Pelaku Usaha | NIB | Penanaman Modal | Skala | Lokasi — stream PDF landscape
        Route::get('/rekap/cetak', [RekapController::class, 'rekap'])
            ->name('sanksi-administratif.rekap');
    });

    Route::resource('/sanksi-administratif', SanksiAdministratifController::class)->parameters(['sanksi-administratif' => 'sanksiAdministratif']);
});

// ── Pengaduan Masyarakat (Public) ──
Route::get('/pengaduan', [PengaduanController::class, 'create'])->name('pengaduan.create');
Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');

// ── Survei Kepuasan Masyarakat / SKM (Public) ──
Route::get('/skm', [SkmController::class, 'create'])->name('skm.create');
Route::post('/skm', [SkmController::class, 'store'])->name('skm.store');

// ── Admin: Pengaduan Masyarakat ──
Route::prefix('admin/pengaduan')->name('admin.pengaduan.')->middleware('auth')->group(function () {
    Route::get('/', [AdminPengaduanController::class, 'index'])->name('index');
    Route::get('/{pengaduan}', [AdminPengaduanController::class, 'show'])->name('show');
    Route::patch('/{pengaduan}/status', [AdminPengaduanController::class, 'updateStatus'])->name('status.update');
    Route::get('/{pengaduan}/lampiran/{jenis}', [AdminPengaduanController::class, 'downloadLampiran'])->name('lampiran.download');
    Route::get('/{pengaduan}/lampiran/{jenis}/preview', [AdminPengaduanController::class, 'previewLampiran'])->name('lampiran.preview');
    Route::delete('/{pengaduan}', [AdminPengaduanController::class, 'destroy'])->name('destroy');
});

// ── Admin: SKM ──
Route::prefix('admin/skm')->name('admin.skm.')->middleware('auth')->group(function () {
    Route::get('/', [AdminSkmController::class, 'index'])->name('index');
    Route::get('/{skm}', [AdminSkmController::class, 'show'])->name('show');
    Route::delete('/{skm}', [AdminSkmController::class, 'destroy'])->name('destroy');
});

Route::get('/phpinfo', function() {
    phpinfo();
});

Route::get('/print', function() {
    return view('template.sp1');
});


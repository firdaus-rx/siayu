<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-primary-900 text-white">
            <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/10">
                        <img src="/logo-pidie.svg" alt="Logo" class="h-6 w-6" />
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-tight">SIAYU Kabupaten Pidie</h1>
                        <p class="text-xs text-white/60">Sistem Pengawasan Kepatuhan</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Konten -->
        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
            <!-- Judul -->
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold text-gray-900">Statistik Layanan Publik</h2>
                <p class="mt-2 text-sm text-gray-500">
                    Data pengaduan masyarakat dan hasil survei kepuasan terhadap pelayanan DPMPTSP Kabupaten Pidie
                </p>
            </div>

            <!-- Statistik Pengaduan -->
            <div class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Status Pengaduan Masyarakat</h3>
                <div class="mb-4 grid grid-cols-2 gap-4 sm:grid-cols-5">
                    <div class="rounded-lg bg-gray-50 p-4 text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ pengaduanStats.total }}</p>
                        <p class="text-xs text-gray-500">Total</p>
                    </div>
                    <div class="rounded-lg bg-yellow-50 p-4 text-center">
                        <p class="text-2xl font-bold text-yellow-600">{{ pengaduanStats.pending }}</p>
                        <p class="text-xs text-yellow-600">Pending</p>
                    </div>
                    <div class="rounded-lg bg-blue-50 p-4 text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ pengaduanStats.proses }}</p>
                        <p class="text-xs text-blue-600">Proses</p>
                    </div>
                    <div class="rounded-lg bg-green-50 p-4 text-center">
                        <p class="text-2xl font-bold text-green-600">{{ pengaduanStats.selesai }}</p>
                        <p class="text-xs text-green-600">Selesai</p>
                    </div>
                    <div class="rounded-lg bg-red-50 p-4 text-center">
                        <p class="text-2xl font-bold text-red-600">{{ pengaduanStats.tidak_dapat_ditindaklanjuti }}</p>
                        <p class="text-xs text-red-600">Tidak Dapat</p>
                    </div>
                </div>
                <div class="h-64">
                    <canvas ref="pengaduanChart"></canvas>
                </div>
            </div>

            <!-- Statistik SKM -->
            <div class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Survei Kepuasan Masyarakat (SKM)</h3>
                <div class="mb-4 grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div class="rounded-lg bg-gray-50 p-4 text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ skmStats.total }}</p>
                        <p class="text-xs text-gray-500">Total Responden</p>
                    </div>
                    <div class="rounded-lg bg-primary-50 p-4 text-center">
                        <p class="text-2xl font-bold text-primary-600">{{ skmStats.rata_rata }}</p>
                        <p class="text-xs text-primary-600">Rata-rata Skor</p>
                    </div>
                    <div class="rounded-lg bg-green-50 p-4 text-center">
                        <p class="text-2xl font-bold text-green-600">{{ skmStats.distribusi_jawaban.puas + skmStats.distribusi_jawaban.sangat_puas }}</p>
                        <p class="text-xs text-green-600">Puas</p>
                    </div>
                    <div class="rounded-lg bg-red-50 p-4 text-center">
                        <p class="text-2xl font-bold text-red-600">{{ skmStats.distribusi_jawaban.sangat_tidak_puas + skmStats.distribusi_jawaban.tidak_puas }}</p>
                        <p class="text-xs text-red-600">Tidak Puas</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div>
                        <h4 class="mb-2 text-sm font-medium text-gray-700">Distribusi Jawaban (Pertanyaan 1)</h4>
                        <div class="h-64">
                            <canvas ref="skmPieChart"></canvas>
                        </div>
                    </div>
                    <div>
                        <h4 class="mb-2 text-sm font-medium text-gray-700">Jenis Layanan yang Diterima</h4>
                        <div class="h-64">
                            <canvas ref="skmBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-wrap justify-center gap-4">
                <Link href="/pengaduan" class="rounded-lg bg-primary-600 px-6 py-3 text-sm font-medium text-white hover:bg-primary-700">
                    Buat Pengaduan
                </Link>
                <Link href="/skm" class="rounded-lg border border-primary-600 px-6 py-3 text-sm font-medium text-primary-600 hover:bg-primary-50">
                    Isi Survei
                </Link>
                <Link href="/login" class="rounded-lg border border-gray-300 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Masuk
                </Link>
            </div>

            <!-- Footer -->
            <p class="mt-8 text-center text-xs text-gray-400">
                DPMPTSP Kabupaten Pidie &copy; {{ new Date().getFullYear() }}
            </p>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
    pengaduanStats: Object,
    skmStats: Object,
});

const pengaduanChart = ref(null);
const skmPieChart = ref(null);
const skmBarChart = ref(null);

onMounted(() => {
    // Grafik Batang - Status Pengaduan
    new Chart(pengaduanChart.value, {
        type: 'bar',
        data: {
            labels: ['Pending', 'Proses', 'Selesai', 'Tidak Dapat Ditindaklanjuti'],
            datasets: [{
                label: 'Jumlah Pengaduan',
                data: [
                    props.pengaduanStats.pending,
                    props.pengaduanStats.proses,
                    props.pengaduanStats.selesai,
                    props.pengaduanStats.tidak_dapat_ditindaklanjuti,
                ],
                backgroundColor: [
                    'rgba(234, 179, 8, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                ],
                borderColor: [
                    'rgb(234, 179, 8)',
                    'rgb(59, 130, 246)',
                    'rgb(34, 197, 94)',
                    'rgb(239, 68, 68)',
                ],
                borderWidth: 1,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                    },
                },
            },
        },
    });

    // Grafik Pie - Distribusi Jawaban SKM
    new Chart(skmPieChart.value, {
        type: 'doughnut',
        data: {
            labels: ['Sangat Tidak Puas', 'Tidak Puas', 'Puas', 'Sangat Puas'],
            datasets: [{
                data: [
                    props.skmStats.distribusi_jawaban.sangat_tidak_puas,
                    props.skmStats.distribusi_jawaban.tidak_puas,
                    props.skmStats.distribusi_jawaban.puas,
                    props.skmStats.distribusi_jawaban.sangat_puas,
                ],
                backgroundColor: [
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(249, 115, 22, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                ],
                borderColor: [
                    'rgb(239, 68, 68)',
                    'rgb(249, 115, 22)',
                    'rgb(59, 130, 246)',
                    'rgb(34, 197, 94)',
                ],
                borderWidth: 1,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
            },
        },
    });

    // Grafik Batang - Jenis Layanan SKM
    new Chart(skmBarChart.value, {
        type: 'bar',
        data: {
            labels: props.skmStats.jenis_layanan.map(item => item.jenis_layanan),
            datasets: [{
                label: 'Jumlah Responden',
                data: props.skmStats.jenis_layanan.map(item => item.total),
                backgroundColor: 'rgba(139, 28, 19, 0.8)',
                borderColor: 'rgb(139, 28, 19)',
                borderWidth: 1,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                    },
                },
            },
        },
    });
});
</script>

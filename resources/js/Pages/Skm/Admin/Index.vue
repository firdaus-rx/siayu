<template>
    <AdminLayout title="Survei Kepuasan Masyarakat">
        <!-- Header -->
        <div class="mb-4 flex items-start justify-between gap-3">
            <div>
                <h1 class="text-[17px] font-bold leading-none tracking-tight text-gray-900">Survei Kepuasan Masyarakat</h1>
                <p class="mt-1 text-xs text-gray-500">Hasil survei kepuasan masyarakat terhadap pelayanan</p>
            </div>
        </div>

        <!-- Statistik Utama -->
        <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white px-4 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Total Responden</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ stats.total }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white px-4 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Rata-rata Skor</p>
                <p class="mt-1 text-2xl font-bold text-primary-600">{{ stats.rata_rata }}</p>
                <p class="text-xs text-gray-400">dari skala 4.00</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white px-4 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Tingkat Kepuasan</p>
                <p class="mt-1 text-2xl font-bold" :class="kepuasanColor">{{ kepuasanLabel }}</p>
                <p class="text-xs text-gray-400">{{ kepuasanPersen }}% dari total</p>
            </div>
        </div>

        <!-- Distribusi Jenis Layanan -->
        <div class="mb-4 rounded-xl border border-gray-200 bg-white">
            <div class="border-b border-gray-100 px-4 py-3">
                <h3 class="text-sm font-semibold text-gray-800">Distribusi Jenis Layanan</h3>
            </div>
            <div class="p-4">
                <div v-for="item in layananStats" :key="item.jenis_layanan" class="mb-2 flex items-center justify-between">
                    <span class="text-sm text-gray-700">{{ item.jenis_layanan }}</span>
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-32 overflow-hidden rounded-full bg-gray-100">
                            <div class="h-full rounded-full bg-primary-500" :style="{ width: persen(item.total, stats.total) + '%' }" />
                        </div>
                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-700">{{ item.total }}</span>
                    </div>
                </div>
                <p v-if="!layananStats.length" class="py-4 text-center text-sm text-gray-400">Belum ada data.</p>
            </div>
        </div>

        <!-- Demografi -->
        <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
            <!-- Jenis Kelamin -->
            <div class="rounded-xl border border-gray-200 bg-white">
                <div class="border-b border-gray-100 px-4 py-3">
                    <h3 class="text-sm font-semibold text-gray-800">Jenis Kelamin</h3>
                </div>
                <div class="p-4">
                    <div v-for="item in demografi.jenis_kelamin" :key="item.jenis_kelamin" class="mb-2 flex items-center justify-between">
                        <span class="text-sm text-gray-700">{{ item.jenis_kelamin === 'laki-laki' ? 'Laki-laki' : 'Perempuan' }}</span>
                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-700">{{ item.total }}</span>
                    </div>
                </div>
            </div>

            <!-- Pendidikan -->
            <div class="rounded-xl border border-gray-200 bg-white">
                <div class="border-b border-gray-100 px-4 py-3">
                    <h3 class="text-sm font-semibold text-gray-800">Pendidikan Terakhir</h3>
                </div>
                <div class="p-4">
                    <div v-for="item in demografi.pendidikan" :key="item.pendidikan" class="mb-2 flex items-center justify-between">
                        <span class="text-sm text-gray-700">{{ item.pendidikan }}</span>
                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-700">{{ item.total }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rata-rata per Pertanyaan -->
        <div class="mb-4 rounded-xl border border-gray-200 bg-white">
            <div class="border-b border-gray-100 px-4 py-3">
                <h3 class="text-sm font-semibold text-gray-800">Rata-rata Skor per Pertanyaan</h3>
            </div>
            <div class="p-4">
                <div v-for="(label, field) in pertanyaanLabels" :key="field" class="mb-3">
                    <div class="mb-1 flex items-center justify-between">
                        <span class="text-xs text-gray-600">{{ label }}</span>
                        <span class="text-xs font-semibold text-gray-900">{{ stats.rata_rata_per_pertanyaan?.[field] || 0 }}</span>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-gray-100">
                        <div class="h-full rounded-full bg-primary-500" :style="{ width: ((stats.rata_rata_per_pertanyaan?.[field] || 0) / 4 * 100) + '%' }" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Respons -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <div class="border-b border-gray-100 px-4 py-3">
                <h3 class="text-sm font-semibold text-gray-800">Daftar Respons</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600 whitespace-nowrap">No</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600 whitespace-nowrap">Tanggal</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600 whitespace-nowrap">Layanan</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600 whitespace-nowrap">Skor</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="(item, index) in skm.data" :key="item.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ index + 1 }}</td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ formatDate(item.created_at) }}</td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ item.jenis_layanan }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold"
                                    :class="skorBadge(item.skor_rata_rata)">
                                    {{ item.skor_rata_rata }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <Link :href="`/admin/skm/${item.id}`" class="inline-flex items-center gap-1 rounded-lg bg-primary-50 px-2.5 py-1.5 text-xs font-medium text-primary-700 hover:bg-primary-100">
                                        <Eye :size="12" /> Lihat
                                    </Link>
                                    <button @click="confirmHapus(item.id)" class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100">
                                        <Trash2 :size="12" /> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!skm.data.length">
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada data survei.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="skm.last_page > 1" class="flex items-center justify-between border-t border-gray-200 px-4 py-3">
                <p class="text-xs text-gray-500">
                    Menampilkan {{ skm.from }} - {{ skm.to }} dari {{ skm.total }} data
                </p>
                <div class="flex gap-1">
                    <Link v-for="link in skm.links" :key="link.label"
                        :href="link.url"
                        class="rounded px-2 py-1 text-xs"
                        :class="link.active ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                        v-html="link.label" />
                </div>
            </div>
        </div>
        <!-- Modal Hapus -->
        <DeleteModal ref="deleteModal" title="Hapus Data SKM" message="Apakah Anda yakin ingin menghapus data survei ini? Tindakan ini tidak dapat dibatalkan." @confirm="hapus" />
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Eye, Trash2 } from '@lucide/vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteModal from '@/Components/DeleteModal.vue';

const props = defineProps({
    skm: Object,
    filters: Object,
    stats: Object,
    layananStats: Array,
    demografi: Object,
    flash: Object,
});

const pertanyaanLabels = {
    p1_kesesuaian_persyaratan: 'Kesesuaian persyaratan',
    p2_kemudahan_prosedur: 'Kemudahan prosedur',
    p3_jadwal_waktu: 'Jadwal dan waktu',
    p4_tarif_biaya: 'Tarif/biaya',
    p5_produk_hasil: 'Produk hasil pelayanan',
    p6_kompetensi_petugas: 'Kompetensi petugas',
    p7_perilaku_petugas: 'Perilaku petugas',
    p8_sarana_prasarana: 'Sarana dan prasarana',
    p9_penanganan_pengaduan: 'Penanganan pengaduan',
};

const kepuasanPersen = computed(() => {
    if (!props.stats.total) return 0;
    const puas = props.stats.distribusi_jawaban?.p1_kesesuaian_persyaratan?.[3] || 0;
    const sangatPuas = props.stats.distribusi_jawaban?.p1_kesesuaian_persyaratan?.[4] || 0;
    return Math.round(((puas + sangatPuas) / props.stats.total) * 100);
});

const kepuasanLabel = computed(() => {
    const p = kepuasanPersen.value;
    if (p >= 80) return 'Sangat Puas';
    if (p >= 60) return 'Puas';
    if (p >= 40) return 'Cukup Puas';
    return 'Kurang Puas';
});

const kepuasanColor = computed(() => {
    const p = kepuasanPersen.value;
    if (p >= 80) return 'text-green-600';
    if (p >= 60) return 'text-blue-600';
    if (p >= 40) return 'text-yellow-600';
    return 'text-red-600';
});

function persen(value, total) {
    return total ? Math.round((value / total) * 100) : 0;
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });
}

function skorBadge(skor) {
    if (skor >= 3.5) return 'bg-green-100 text-green-800';
    if (skor >= 2.5) return 'bg-blue-100 text-blue-800';
    if (skor >= 1.5) return 'bg-yellow-100 text-yellow-800';
    return 'bg-red-100 text-red-800';
}

const deleteModal = ref(null);
const deleteId = ref(null);

function confirmHapus(id) {
    deleteId.value = id;
    deleteModal.value.open();
}

function hapus() {
    if (deleteId.value) {
        router.delete(`/admin/skm/${deleteId.value}`);
    }
}
</script>

<template>
    <AdminLayout title="Pengaduan Masyarakat">
        <!-- Header -->
        <div class="mb-4 flex items-start justify-between gap-3">
            <div>
                <h1 class="text-[17px] font-bold leading-none tracking-tight text-gray-900">Pengaduan Masyarakat</h1>
                <p class="mt-1 text-xs text-gray-500">Kelola dan tindaklanjuti pengaduan dari masyarakat</p>
            </div>
        </div>

        <!-- Statistik -->
        <div class="mb-4 grid grid-cols-2 gap-3 sm:grid-cols-5">
            <div class="rounded-xl border border-gray-200 bg-white px-3.5 py-3">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Total</p>
                <p class="mt-1 text-xl font-bold text-gray-900">{{ stats.total }}</p>
            </div>
            <div class="rounded-xl border border-yellow-200 bg-yellow-50 px-3.5 py-3">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-yellow-600">Pending</p>
                <p class="mt-1 text-xl font-bold text-yellow-700">{{ stats.pending }}</p>
                <p class="text-[11px] text-yellow-600">{{ persen(stats.pending, stats.total) }}%</p>
            </div>
            <div class="rounded-xl border border-blue-200 bg-blue-50 px-3.5 py-3">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-blue-600">Proses</p>
                <p class="mt-1 text-xl font-bold text-blue-700">{{ stats.proses }}</p>
                <p class="text-[11px] text-blue-600">{{ persen(stats.proses, stats.total) }}%</p>
            </div>
            <div class="rounded-xl border border-green-200 bg-green-50 px-3.5 py-3">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-green-600">Selesai</p>
                <p class="mt-1 text-xl font-bold text-green-700">{{ stats.selesai }}</p>
                <p class="text-[11px] text-green-600">{{ persen(stats.selesai, stats.total) }}%</p>
            </div>
            <div class="rounded-xl border border-red-200 bg-red-50 px-3.5 py-3">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-red-600">Tidak Dapat</p>
                <p class="mt-1 text-xl font-bold text-red-700">{{ stats.tidak_dapat_ditindaklanjuti }}</p>
                <p class="text-[11px] text-red-600">{{ persen(stats.tidak_dapat_ditindaklanjuti, stats.total) }}%</p>
            </div>
        </div>

        <!-- Filter -->
        <div class="mb-4 flex flex-wrap items-center gap-2">
            <input v-model="search" type="text" placeholder="Cari nama, NIK, materi..."
                class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500" />
            <select v-model="statusFilter" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="proses">Proses</option>
                <option value="selesai">Selesai</option>
                <option value="tidak_dapat_ditindaklanjuti">Tidak Dapat Ditindaklanjuti</option>
            </select>
            <button @click="applyFilter" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">
                Filter
            </button>
        </div>

        <!-- Tabel -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600">No</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600">Tanggal</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600">Nama</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600">Materi</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="(item, index) in pengaduan.data" :key="item.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">{{ index + 1 }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ formatDate(item.created_at) }}</td>
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-900">{{ item.nama_lengkap }}</p>
                                <p class="text-xs text-gray-500">{{ item.nik || '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ item.materi_pengaduan || '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold"
                                    :class="statusBadge(item.status)">
                                    {{ statusLabel(item.status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <Link :href="`/admin/pengaduan/${item.id}`" class="text-primary-600 hover:underline">
                                        Lihat
                                    </Link>
                                    <button @click="confirmHapus(item.id)" class="text-red-600 hover:underline">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!pengaduan.data.length">
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada data pengaduan.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="pengaduan.last_page > 1" class="flex items-center justify-between border-t border-gray-200 px-4 py-3">
                <p class="text-xs text-gray-500">
                    Menampilkan {{ pengaduan.from }} - {{ pengaduan.to }} dari {{ pengaduan.total }} data
                </p>
                <div class="flex gap-1">
                    <Link v-for="link in pengaduan.links" :key="link.label"
                        :href="link.url"
                        class="rounded px-2 py-1 text-xs"
                        :class="link.active ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                        v-html="link.label" />
                </div>
            </div>
        </div>

        <!-- Statistik per Materi -->
        <div class="mt-6 rounded-xl border border-gray-200 bg-white">
            <div class="border-b border-gray-100 px-4 py-3">
                <h3 class="text-sm font-semibold text-gray-800">Statistik per Materi Pengaduan</h3>
            </div>
            <div class="p-4">
                <div v-for="item in materiStats" :key="item.materi" class="mb-2 flex items-center justify-between">
                    <span class="text-sm text-gray-700">{{ item.materi }}</span>
                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-700">{{ item.total }}</span>
                </div>
                <p v-if="!materiStats.length" class="py-4 text-center text-sm text-gray-400">Belum ada data.</p>
            </div>
        </div>

        <!-- Modal Hapus -->
        <DeleteModal ref="deleteModal" title="Hapus Pengaduan" message="Apakah Anda yakin ingin menghapus pengaduan ini? Tindakan ini tidak dapat dibatalkan." @confirm="hapus" />
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteModal from '@/Components/DeleteModal.vue';

const props = defineProps({
    pengaduan: Object,
    filters: Object,
    stats: Object,
    materiStats: Array,
    flash: Object,
});

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');

const deleteModal = ref(null);
const deleteId = ref(null);

function confirmHapus(id) {
    deleteId.value = id;
    deleteModal.value.open();
}

function hapus() {
    if (deleteId.value) {
        router.delete(`/admin/pengaduan/${deleteId.value}`);
    }
}

function applyFilter() {
    router.get('/admin/pengaduan', {
        search: search.value,
        status: statusFilter.value,
    }, { preserveState: true });
}

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

function statusLabel(status) {
    const labels = {
        pending: 'Pending',
        proses: 'Proses',
        selesai: 'Selesai',
        tidak_dapat_ditindaklanjuti: 'Tidak Dapat Ditindaklanjuti',
    };
    return labels[status] || status;
}

function statusBadge(status) {
    const badges = {
        pending: 'bg-yellow-100 text-yellow-800',
        proses: 'bg-blue-100 text-blue-800',
        selesai: 'bg-green-100 text-green-800',
        tidak_dapat_ditindaklanjuti: 'bg-red-100 text-red-800',
    };
    return badges[status] || 'bg-gray-100 text-gray-800';
}
</script>

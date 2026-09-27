<template>
    <AdminLayout title="Detail Pengaduan">
        <div class="mx-auto max-w-3xl">
            <!-- Back -->
            <Link href="/admin/pengaduan" class="mb-4 inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900">
                <ArrowLeft :size="14" /> Kembali
            </Link>

            <!-- Toast Sweet Alert ditampilkan otomatis via AdminLayout -->

            <!-- Detail -->
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900">Detail Pengaduan</h2>
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold"
                            :class="statusBadge(pengaduan.status)">
                            {{ statusLabel(pengaduan.status) }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Diajukan pada {{ formatDate(pengaduan.created_at) }}</p>
                </div>

                <div class="space-y-6 px-6 py-6">
                    <!-- Identitas -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Identitas Pengadu</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-xs text-gray-500">Nama Lengkap</p>
                                <p class="text-sm font-medium text-gray-900">{{ pengaduan.nama_lengkap }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">NIK / No. Identitas</p>
                                <p class="text-sm font-medium text-gray-900">{{ pengaduan.nik || '-' }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-xs text-gray-500">Alamat</p>
                                <p class="text-sm font-medium text-gray-900">{{ pengaduan.alamat || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">No. Telepon/HP</p>
                                <p class="text-sm font-medium text-gray-900">{{ pengaduan.no_telepon || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="text-sm font-medium text-gray-900">{{ pengaduan.email || '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Materi -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Materi Pengaduan</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Materi yang Dilaporkan</p>
                                <p class="text-sm font-medium text-gray-900">{{ pengaduan.materi_pengaduan || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Deskripsi Pengaduan</p>
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ pengaduan.deskripsi_pengaduan }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Lampiran -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Lampiran</h3>
                        <div class="space-y-4">
                            <!-- KTP Preview -->
                            <div v-if="pengaduan.lampiran_ktp">
                                <p class="mb-2 text-xs font-medium text-gray-700">KTP</p>
                                <div class="overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                                    <img v-if="isGambar(pengaduan.lampiran_ktp)" :src="`/admin/pengaduan/${pengaduan.id}/lampiran/ktp/preview`" alt="KTP" class="max-h-96 w-full object-contain" />
                                    <iframe v-else :src="`/admin/pengaduan/${pengaduan.id}/lampiran/ktp/preview`" class="h-96 w-full" title="KTP"></iframe>
                                </div>
                            </div>
                            <!-- Lampiran Lainnya Preview -->
                            <div v-if="pengaduan.lampiran_lainnya">
                                <p class="mb-2 text-xs font-medium text-gray-700">Lampiran Lainnya</p>
                                <div class="overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                                    <img v-if="isGambar(pengaduan.lampiran_lainnya)" :src="`/admin/pengaduan/${pengaduan.id}/lampiran/lainnya/preview`" alt="Lampiran Lainnya" class="max-h-96 w-full object-contain" />
                                    <iframe v-else :src="`/admin/pengaduan/${pengaduan.id}/lampiran/lainnya/preview`" class="h-96 w-full" title="Lampiran Lainnya"></iframe>
                                </div>
                            </div>
                            <p v-if="!pengaduan.lampiran_ktp && !pengaduan.lampiran_lainnya" class="text-sm text-gray-400">Tidak ada lampiran.</p>
                        </div>
                    </div>

                    <!-- Update Status -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Tindak Lanjut</h3>
                        <form @submit.prevent="updateStatus" class="space-y-4">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-700">Status</label>
                                <select v-model="form.status" required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500">
                                    <option value="pending">Pending</option>
                                    <option value="proses">Proses</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="tidak_dapat_ditindaklanjuti">Tidak Dapat Ditindaklanjuti</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-700">Catatan Admin</label>
                                <textarea v-model="form.catatan_admin" rows="3"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500"
                                    placeholder="Catatan penanganan pengaduan..."></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">
                                    Update Status
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Hapus -->
                    <div class="border-t border-gray-100 pt-4">
                        <button @click="confirmHapus" class="rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                            Hapus Pengaduan
                        </button>
                    </div>
                </div>
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
import { ArrowLeft } from '@lucide/vue';

const props = defineProps({
    pengaduan: Object,
    flash: Object,
});

const form = ref({
    status: props.pengaduan.status,
    catatan_admin: props.pengaduan.catatan_admin || '',
});

function updateStatus() {
    router.patch(`/admin/pengaduan/${props.pengaduan.id}/status`, form.value);
}

const deleteModal = ref(null);

function confirmHapus() {
    deleteModal.value.open();
}

function hapus() {
    router.delete(`/admin/pengaduan/${props.pengaduan.id}`);
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function isGambar(path) {
    if (!path) return false;
    const ext = path.split('.').pop()?.toLowerCase();
    return ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'].includes(ext);
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

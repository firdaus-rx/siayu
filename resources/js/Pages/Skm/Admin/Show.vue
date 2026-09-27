<template>
    <AdminLayout title="Detail SKM">
        <div class="mx-auto max-w-3xl">
            <!-- Back -->
            <Link href="/admin/skm" class="mb-4 inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900">
                <ArrowLeft :size="14" /> Kembali
            </Link>

            <!-- Detail -->
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900">Detail Respons SKM</h2>
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold"
                            :class="skorBadge(skm.skor_rata_rata)">
                            Skor: {{ skm.skor_rata_rata }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Disubmit pada {{ formatDate(skm.created_at) }}</p>
                </div>

                <div class="space-y-6 px-6 py-6">
                    <!-- Demografi -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Data Responden</h3>
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                            <div>
                                <p class="text-xs text-gray-500">Jenis Kelamin</p>
                                <p class="text-sm font-medium text-gray-900">{{ skm.jenis_kelamin === 'laki-laki' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Usia</p>
                                <p class="text-sm font-medium text-gray-900">{{ skm.usia }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Pendidikan</p>
                                <p class="text-sm font-medium text-gray-900">{{ skm.pendidikan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Pekerjaan</p>
                                <p class="text-sm font-medium text-gray-900">{{ skm.pekerjaan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Jenis Layanan</p>
                                <p class="text-sm font-medium text-gray-900">{{ skm.jenis_layanan }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Jawaban -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Jawaban Survei</h3>
                        <div class="space-y-3">
                            <div v-for="(label, field) in pertanyaanLabels" :key="field" class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                                <span class="text-sm text-gray-700">{{ label }}</span>
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold"
                                    :class="jawabanBadge(skm[field])">
                                    {{ jawabanLabel(skm[field]) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Saran -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Saran Perbaikan</h3>
                        <div class="rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ skm.saran_perbaikan || '-' }}</p>
                        </div>
                    </div>

                    <!-- Hapus -->
                    <div class="border-t border-gray-100 pt-4">
                        <button @click="confirmHapus" class="rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                            Hapus Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Hapus -->
        <DeleteModal ref="deleteModal" title="Hapus Data SKM" message="Apakah Anda yakin ingin menghapus data survei ini? Tindakan ini tidak dapat dibatalkan." @confirm="hapus" />
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DeleteModal from '@/Components/DeleteModal.vue';
import { ArrowLeft } from '@lucide/vue';

const props = defineProps({
    skm: Object,
});

const pertanyaanLabels = {
    p1_kesesuaian_persyaratan: 'Kesesuaian persyaratan dengan jenis pelayanan',
    p2_kemudahan_prosedur: 'Kemudahan prosedur pelayanan',
    p3_jadwal_waktu: 'Jadwal dan waktu yang ditetapkan petugas',
    p4_tarif_biaya: 'Tarif/biaya yang dikenakan',
    p5_produk_hasil: 'Produk hasil pelayanan',
    p6_kompetensi_petugas: 'Kompetensi petugas',
    p7_perilaku_petugas: 'Perilaku petugas (kesopanan & keramahan)',
    p8_sarana_prasarana: 'Sarana dan prasarana pelayanan',
    p9_penanganan_pengaduan: 'Penanganan pengaduan, saran dan masukan',
};

const jawabanLabels = {
    1: 'Sangat Tidak Puas',
    2: 'Tidak Puas',
    3: 'Puas',
    4: 'Sangat Puas',
};

function jawabanLabel(value) {
    return jawabanLabels[value] || '-';
}

function jawabanBadge(value) {
    if (value >= 4) return 'bg-green-100 text-green-800';
    if (value === 3) return 'bg-blue-100 text-blue-800';
    if (value === 2) return 'bg-yellow-100 text-yellow-800';
    return 'bg-red-100 text-red-800';
}

function skorBadge(skor) {
    if (skor >= 3.5) return 'bg-green-100 text-green-800';
    if (skor >= 2.5) return 'bg-blue-100 text-blue-800';
    if (skor >= 1.5) return 'bg-yellow-100 text-yellow-800';
    return 'bg-red-100 text-red-800';
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

const deleteModal = ref(null);

function confirmHapus() {
    deleteModal.value.open();
}

function hapus() {
    router.delete(`/admin/skm/${props.skm.id}`);
}
</script>

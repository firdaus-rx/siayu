<template>
    <PublicLayout title="Survei Kepuasan Masyarakat">
        <!-- Konten -->
        <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6">
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <!-- Judul -->
                <div class="border-b border-gray-100 px-6 py-5">
                    <h2 class="text-lg font-bold text-gray-900">Survei Kepuasan Masyarakat (SKM)</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Survei ini bertujuan untuk mengukur tingkat kepuasan masyarakat terhadap pelayanan yang diberikan.
                        Jawaban Bapak/Ibu sangat membantu dalam peningkatan kualitas pelayanan kami.
                        Mohon mengisi dengan jujur sesuai pengalaman yang diterima.
                    </p>
                </div>

                <!-- Toast Sweet Alert ditampilkan otomatis via window.Toast -->

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-6 px-6 py-6">
                    <!-- Demografi -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Data Responden</h3>
                        <div class="space-y-4">
                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="mb-2 block text-xs font-medium text-gray-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 text-sm">
                                        <input v-model="form.jenis_kelamin" type="radio" value="laki-laki" class="text-primary-600" />
                                        Laki-laki
                                    </label>
                                    <label class="flex items-center gap-2 text-sm">
                                        <input v-model="form.jenis_kelamin" type="radio" value="perempuan" class="text-primary-600" />
                                        Perempuan
                                    </label>
                                </div>
                            </div>

                            <!-- Usia -->
                            <div>
                                <label class="mb-2 block text-xs font-medium text-gray-700">Usia <span class="text-red-500">*</span></label>
                                <select v-model="form.usia" required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500">
                                    <option value="">Pilih rentang usia</option>
                                    <option value="<=18">≤ 18 Tahun</option>
                                    <option value="19-25">19 - 25 Tahun</option>
                                    <option value="26-35">26 - 35 Tahun</option>
                                    <option value="36-45">36 - 45 Tahun</option>
                                    <option value="46-55">46 - 55 Tahun</option>
                                    <option value=">=56">≥ 56 Tahun</option>
                                </select>
                            </div>

                            <!-- Pendidikan -->
                            <div>
                                <label class="mb-2 block text-xs font-medium text-gray-700">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                                <select v-model="form.pendidikan" required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500">
                                    <option value="">Pilih pendidikan terakhir</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>

                            <!-- Pekerjaan -->
                            <div>
                                <label class="mb-2 block text-xs font-medium text-gray-700">Pekerjaan <span class="text-red-500">*</span></label>
                                <select v-model="form.pekerjaan" required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500">
                                    <option value="">Pilih pekerjaan</option>
                                    <option value="PNS">PNS</option>
                                    <option value="TNI">TNI</option>
                                    <option value="POLRI">POLRI</option>
                                    <option value="SWASTA">Swasta</option>
                                    <option value="WIRAUSAHA">Wirausaha</option>
                                    <option value="LAINNYA">Lainnya (ketik manual)</option>
                                </select>
                                <input v-if="form.pekerjaan === 'LAINNYA'" v-model="form.pekerjaan_lainnya" type="text" required
                                    class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500"
                                    placeholder="Ketik pekerjaan Anda..." />
                            </div>

                            <!-- Jenis Layanan -->
                            <div>
                                <label class="mb-2 block text-xs font-medium text-gray-700">Jenis Layanan yang Diterima <span class="text-red-500">*</span></label>
                                <select v-model="form.jenis_layanan" required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500">
                                    <option value="">Pilih jenis layanan</option>
                                    <option value="Perizinan Berusaha">Perizinan Berusaha</option>
                                    <option value="Perizinan Non-Berusaha">Perizinan Non-Berusaha</option>
                                    <option value="Konsultasi Informasi">Konsultasi Informasi</option>
                                    <option value="Pengaduan">Pengaduan</option>
                                    <option value="Lainnya">Lainnya (ketik manual)</option>
                                </select>
                                <input v-if="form.jenis_layanan === 'Lainnya'" v-model="form.jenis_layanan_lainnya" type="text" required
                                    class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500"
                                    placeholder="Ketik jenis layanan Anda..." />
                            </div>
                        </div>
                    </div>

                    <!-- Pertanyaan Kepuasan -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Penilaian Pelayanan</h3>
                        <div class="space-y-5">
                            <div v-for="(pertanyaan, index) in pertanyaanList" :key="index" class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                                <p class="mb-3 text-sm font-medium text-gray-800">
                                    {{ index + 1 }}. {{ pertanyaan.label }} <span class="text-red-500">*</span>
                                </p>
                                <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                                    <label v-for="opsi in jawabanOptions" :key="opsi.value"
                                        class="flex cursor-pointer items-center gap-2 rounded-lg border bg-white px-3 py-2 text-xs transition-colors"
                                        :class="form[pertanyaan.field] == opsi.value ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-200 hover:border-gray-300'">
                                        <input v-model="form[pertanyaan.field]" type="radio" :value="opsi.value" class="sr-only" />
                                        {{ opsi.label }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Saran -->
                    <div>
                        <label class="mb-2 block text-xs font-medium text-gray-700">Saran-saran Perbaikan <span class="text-red-500">*</span></label>
                        <textarea v-model="form.saran_perbaikan" rows="4" required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500"
                            placeholder="Tuliskan saran dan masukan Anda untuk perbaikan pelayanan kami..."></textarea>
                    </div>

                    <!-- Submit -->
                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                        <button type="submit" :disabled="processing"
                            class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-medium text-white hover:bg-primary-700 disabled:opacity-50">
                            {{ processing ? 'Mengirim...' : 'Kirim Survei' }}
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </PublicLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const page = usePage();

// Tampilkan toast dari session flash (SweetAlert2)
if (page.props.flash?.success) {
    window.Toast.fire({ icon: 'success', title: page.props.flash.success });
} else if (page.props.flash?.error) {
    window.Toast.fire({ icon: 'error', title: page.props.flash.error });
}

const jawabanOptions = [
    { value: 1, label: 'Sangat Tidak Puas' },
    { value: 2, label: 'Tidak Puas' },
    { value: 3, label: 'Puas' },
    { value: 4, label: 'Sangat Puas' },
];

const pertanyaanList = [
    { field: 'p1_kesesuaian_persyaratan', label: 'Bagaimana pendapat saudara tentang kesesuaian persyaratan dengan jenis pelayanannya' },
    { field: 'p2_kemudahan_prosedur', label: 'Bagaimana pendapat saudara tentang kemudahan prosedur pelayanan di Unit ini' },
    { field: 'p3_jadwal_waktu', label: 'Bagaimana pendapat saudara tentang jadwal dan waktu yang ditetapkan petugas dalam memberikan pelayanan' },
    { field: 'p4_tarif_biaya', label: 'Bagaimana pendapat saudara tentang tarif/biaya yang dikenakan dalam pengurusan perizinan yang telah ditetapkan berdasarkan ketentuan yang berlaku' },
    { field: 'p5_produk_hasil', label: 'Bagaimana pendapat saudara tentang produk hasil pelayanan yang diberikan dan diterima telah sesuai dengan jenis perizinan yang ditetapkan di unit pelayanan ini' },
    { field: 'p6_kompetensi_petugas', label: 'Bagaimana pendapat saudara tentang kompetensi petugas dalam memberikan pelayanan' },
    { field: 'p7_perilaku_petugas', label: 'Bagaimana pendapat saudara tentang perilaku petugas dalam pelayanan terkait kesopanan dan keramahan' },
    { field: 'p8_sarana_prasarana', label: 'Bagaimana pendapat saudara tentang sarana dan prasarana pelayanan' },
    { field: 'p9_penanganan_pengaduan', label: 'Bagaimana pendapat saudara tentang cara penanganan pengaduan, saran dan masukan yang telah ditetapkan termasuk tindak lanjut' },
];

const form = ref({
    jenis_kelamin: '',
    usia: '',
    pendidikan: '',
    pekerjaan: '',
    pekerjaan_lainnya: '',
    jenis_layanan: '',
    jenis_layanan_lainnya: '',
    p1_kesesuaian_persyaratan: null,
    p2_kemudahan_prosedur: null,
    p3_jadwal_waktu: null,
    p4_tarif_biaya: null,
    p5_produk_hasil: null,
    p6_kompetensi_petugas: null,
    p7_perilaku_petugas: null,
    p8_sarana_prasarana: null,
    p9_penanganan_pengaduan: null,
    saran_perbaikan: '',
});

const processing = ref(false);


function submit() {
    processing.value = true;

    // Jika pilih "Lainnya", gunakan input manual
    const payload = { ...form.value };
    if (form.value.pekerjaan === 'LAINNYA') {
        payload.pekerjaan = form.value.pekerjaan_lainnya;
    }
    if (form.value.jenis_layanan === 'Lainnya') {
        payload.jenis_layanan = form.value.jenis_layanan_lainnya;
    }

    router.post('/skm', payload, {
        onSuccess: () => {
            processing.value = false;
            form.value = {
                jenis_kelamin: '',
                usia: '',
                pendidikan: '',
                pekerjaan: '',
                pekerjaan_lainnya: '',
                jenis_layanan: '',
                jenis_layanan_lainnya: '',
                p1_kesesuaian_persyaratan: null,
                p2_kemudahan_prosedur: null,
                p3_jadwal_waktu: null,
                p4_tarif_biaya: null,
                p5_produk_hasil: null,
                p6_kompetensi_petugas: null,
                p7_perilaku_petugas: null,
                p8_sarana_prasarana: null,
                p9_penanganan_pengaduan: null,
                saran_perbaikan: '',
            };
        },
        onError: () => {
            processing.value = false;
        },
    });
}
</script>

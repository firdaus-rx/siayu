<template>
    <PublicLayout title="Pengaduan Masyarakat">
        <!-- Konten -->
        <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6">
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <!-- Judul -->
                <div class="border-b border-gray-100 px-6 py-5">
                    <h2 class="text-lg font-bold text-gray-900">Formulir Pengaduan Masyarakat</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Silakan adukan segala persoalan yang Anda hadapi dalam proses pelayanan publik pada
                        DPMPTSP Kabupaten Pidie. Data pribadi Anda terjamin kerahasiaannya.
                    </p>
                </div>

                <!-- Toast Sweet Alert ditampilkan otomatis via window.Toast -->

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-6 px-6 py-6">
                    <!-- Identitas Pengadu -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Identitas Pengadu</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input v-model="form.nama_lengkap" type="text" required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500"
                                    placeholder="Nama lengkap Anda" />
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-700">NIK / No. Identitas</label>
                                <input v-model="form.nik" type="text" maxlength="32"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500"
                                    placeholder="Nomor Induk Kependudukan" />
                            </div>
                            <div class="sm:col-span-2">
                                <label class="mb-1 block text-xs font-medium text-gray-700">Alamat</label>
                                <textarea v-model="form.alamat" rows="2"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500"
                                    placeholder="Alamat lengkap Anda"></textarea>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-700">No. Telepon/HP</label>
                                <input v-model="form.no_telepon" type="tel" maxlength="32"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500"
                                    placeholder="08xxxxxxxxxx" />
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-700">Email</label>
                                <input v-model="form.email" type="email"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500"
                                    placeholder="email@contoh.com" />
                            </div>
                        </div>
                    </div>

                    <!-- Materi Pengaduan -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Materi Pengaduan</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-700">Materi yang Dilaporkan</label>
                                <input v-model="form.materi_pengaduan" type="text"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500"
                                    placeholder="Contoh: Pelayanan perizinan berusaha" />
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-700">Deskripsi Pengaduan <span class="text-red-500">*</span></label>
                                <textarea v-model="form.deskripsi_pengaduan" rows="5" required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500"
                                    placeholder="Jelaskan secara rinci pengaduan Anda..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Lampiran -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Lampiran</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-700">
                                    KTP <span class="text-red-500">*</span>
                                    <span class="ml-1 text-gray-400">(Wajib)</span>
                                </label>
                                <input type="file" accept="image/*,.pdf" @change="handleKtpUpload"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-primary-50 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-primary-700 hover:file:bg-primary-100" />
                                <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG, PDF. Maksimal 5MB.</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-700">
                                    Lampiran Lainnya
                                    <span class="ml-1 text-gray-400">(Opsional)</span>
                                </label>
                                <input type="file" accept="image/*,.pdf" @change="handleLainnyaUpload"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-gray-50 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-gray-700 hover:file:bg-gray-100" />
                                <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG, PDF. Maksimal 5MB.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                        <button type="submit" :disabled="processing"
                            class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-medium text-white hover:bg-primary-700 disabled:opacity-50">
                            {{ processing ? 'Mengirim...' : 'Kirim Pengaduan' }}
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

const form = ref({
    nama_lengkap: '',
    nik: '',
    alamat: '',
    no_telepon: '',
    email: '',
    materi_pengaduan: '',
    deskripsi_pengaduan: '',
});

const lampiranKtp = ref(null);
const lampiranLainnya = ref(null);
const processing = ref(false);

function handleKtpUpload(event) {
    lampiranKtp.value = event.target.files[0];
}

function handleLainnyaUpload(event) {
    lampiranLainnya.value = event.target.files[0];
}

function submit() {
    processing.value = true;

    const formData = new FormData();
    Object.entries(form.value).forEach(([key, value]) => {
        formData.append(key, value);
    });

    if (lampiranKtp.value) {
        formData.append('lampiran_ktp', lampiranKtp.value);
    }
    if (lampiranLainnya.value) {
        formData.append('lampiran_lainnya', lampiranLainnya.value);
    }

    router.post('/pengaduan', formData, {
        forceFormData: true,
        onSuccess: () => {
            processing.value = false;
            form.value = {
                nama_lengkap: '',
                nik: '',
                alamat: '',
                no_telepon: '',
                email: '',
                materi_pengaduan: '',
                deskripsi_pengaduan: '',
            };
            lampiranKtp.value = null;
            lampiranLainnya.value = null;
        },
        onError: () => {
            processing.value = false;
        },
    });
}
</script>

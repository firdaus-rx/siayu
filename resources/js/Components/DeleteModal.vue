<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="batal"></div>

                <!-- Modal -->
                <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                    <!-- Icon -->
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-100">
                        <Trash2 :size="24" class="text-red-600" />
                    </div>

                    <!-- Judul -->
                    <h3 class="text-center text-lg font-bold text-gray-900">{{ title }}</h3>

                    <!-- Pesan -->
                    <p class="mt-2 text-center text-sm text-gray-500">{{ message }}</p>

                    <!-- Tombol -->
                    <div class="mt-6 flex gap-3">
                        <button @click="batal" type="button"
                            class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button @click="konfirmasi" type="button"
                            class="flex-1 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref } from 'vue';
import { Trash2 } from '@lucide/vue';

const props = defineProps({
    title: { type: String, default: 'Hapus Data' },
    message: { type: String, default: 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.' },
});

const emit = defineEmits(['confirm', 'cancel']);

const show = ref(false);

function open() {
    show.value = true;
}

function batal() {
    show.value = false;
    emit('cancel');
}

function konfirmasi() {
    show.value = false;
    emit('confirm');
}

defineExpose({ open });
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}
.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>

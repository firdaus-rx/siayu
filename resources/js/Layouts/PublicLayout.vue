<template>
    <div class="min-h-screen bg-white text-oss-base-black font-sans">
        <!-- Top announcement -->
        <div class="bg-yellow-500">
            <div class="mx-auto max-w-1920 px-4 sm:px-10 md:px-16 lg:px-24 xl:px-32">
                <div class="mx-0 grid grid-flow-row grid-cols-12 items-center gap-4 py-2">
                    <div class="col-span-11 flex items-center">
                        <span class="material-symbols-outlined" aria-hidden="true">campaign</span>
                        <div class="ml-4 text-start text-xs font-semibold">
                            <p>Sistem Informasi Aduan Yanlik Unggulan DPMPTSP Kabupaten Pidie — kelola daftar list sanksi pencabutan &amp; usulan pencabutan perizinan berusaha terintegrasi OCR.</p>
                        </div>
                    </div>
                    <button type="button" aria-label="Tutup pengumuman" class="col-span-1 ml-auto ti ti-x text-lg" onclick="this.closest('.bg-yellow-500').remove()"></button>
                </div>
            </div>
        </div>

        <!-- Header -->
        <header class="relative z-50 border-b border-oss-gray-25 bg-oss-gray-25 py-4">
            <div class="mx-auto max-w-[1920px] px-4 sm:px-10 md:px-16 lg:px-24 xl:px-32 flex items-center justify-between">
                <div class="flex items-center space-x-2"><span class="ti ti-device-mobile"></span>
                    <div class="text-sm font-normal hover:underline cursor-pointer">SIAYU</div>
                </div>
                <div class="flex items-center space-x-6">
                    <span class="text-sm font-semibold text-oss-base-black">ID</span>
                    <span class="text-sm font-semibold text-oss-blue-500">Bantuan</span>
                </div>
            </div>
        </header>

        <!-- Navbar -->
        <header class="sticky top-0 z-30 bg-white shadow" style="height:72px">
            <div class="mx-auto max-w-1920 px-4 sm:px-10 md:px-16 lg:px-24 xl:px-32">
                <div class="flex h-[72px] items-center justify-between gap-3">
                    <a href="/" class="flex shrink-0 items-center gap-3" aria-label="SIAYU beranda">
                        <img src="/logo-pidie.svg" alt="Logo Pidie" class="h-9 w-auto" />
                        <span class="hidden sm:block text-sm font-bold leading-tight text-oss-base-black">SIAYU<br><span class="text-xs font-normal text-oss-gray-500">Kabupaten Pidie</span></span>
                    </a>
                    <nav aria-label="Navigasi utama" class="hidden lg:flex items-center gap-1">
                        <a href="/" class="px-3 py-2 text-sm font-semibold hover:text-oss-blue-500">Beranda</a>
                        <div class="relative group">
                            <button type="button" class="px-3 py-2 text-sm font-semibold hover:text-oss-blue-500 flex items-center gap-1">
                                Layanan
                                <svg class="w-3 h-3 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="absolute left-0 top-full hidden group-hover:block min-w-[200px] bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                <a href="/pengaduan" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-oss-blue-500">Pengaduan Masyarakat</a>
                                <a href="/skm" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-oss-blue-500">Survei Kepuasan</a>
                            </div>
                        </div>
                        <a href="/#pengawasan" class="px-3 py-2 text-sm font-semibold hover:text-oss-blue-500">Pengawasan</a>
                    </nav>
                    <div class="flex items-center gap-2">
                        <a href="/login" class="inline-flex items-center px-5 py-2 text-sm font-semibold rounded-md bg-primary text-white hover:bg-primary-700">Masuk</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Konten -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer id="footer" class="bg-[#8b1c13] py-8">
            <div class="mx-auto max-w-1920 px-4 sm:px-10 md:px-16 lg:px-24 xl:px-32">
                <div class="flex flex-col justify-between gap-8 md:flex-row">
                    <div class="flex-1 text-center md:text-left">
                        <p class="text-sm font-bold text-white">SIAYU Kabupaten Pidie</p>
                        <p class="mt-2 text-sm text-white/80">Jln. Tgk. Chiek Direubee No.5 Sigli · Telp. (0653) 23399</p>
                        <p class="mt-6 text-xs text-white/60">© {{ new Date().getFullYear() }} DPMPTSP Kabupaten Pidie</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="text-sm font-bold text-white">Akses Sistem</div>
                        <a href="/login" class="text-sm text-white hover:underline">Masuk</a>
                        <a href="/guest/index.html" class="text-sm text-white/70 hover:underline">Versi Guest Statis</a>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="text-sm font-bold text-white">Layanan Publik</div>
                        <a href="/pengaduan" class="text-sm text-white hover:underline">Pengaduan Masyarakat</a>
                        <a href="/skm" class="text-sm text-white hover:underline">Survei Kepuasan</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    title: { type: String, default: '' },
});
</script>

<style>
    body {
        font-family: 'Montserrat', system-ui, sans-serif;
    }

    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    :where(a, button, input, [tabindex]):focus-visible {
        outline: 2px solid #8b1c13;
        outline-offset: 2px;
        border-radius: 6px;
    }
</style>

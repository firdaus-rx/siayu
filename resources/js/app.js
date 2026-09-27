import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import Swal from 'sweetalert2';

const appName = import.meta.env.VITE_APP_NAME || 'SIAYU';

// Global SweetAlert2 mixin untuk toast
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

window.Toast = Toast;
window.Swal = Swal;

// Fungsi helper untuk menampilkan toast dari session flash
function showFlashToast(props) {
    const flash = props?.flash;
    if (!flash) return;

    // Delay sedikit untuk memastikan DOM sudah siap
    setTimeout(() => {
        if (flash.success) {
            Toast.fire({ icon: 'success', title: flash.success });
        }
        if (flash.error) {
            Toast.fire({ icon: 'error', title: flash.error });
        }
        if (flash.warning) {
            Toast.fire({ icon: 'warning', title: flash.warning });
        }
        if (flash.info) {
            Toast.fire({ icon: 'info', title: flash.info });
        }
    }, 100);
}

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        // Tampilkan toast saat halaman dimuat
        showFlashToast(props);

        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);

        // Listen untuk Inertia events untuk menampilkan toast setelah navigasi
        if (window.Inertia) {
            window.Inertia.on('success', (event) => {
                showFlashToast(event.detail.page?.props);
            });
        }

        return app;
    },
    progress: {
        color: '#8b1c13',
    },
});

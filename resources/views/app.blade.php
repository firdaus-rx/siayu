<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAYU</title>
    <meta name="theme-color" content="#8b1c13" />
    <link rel="icon" href="{{ asset('guest/assets/favicon.ico') }}" sizes="any" />
    <link rel="stylesheet" href="{{ asset('guest/assets/montserrat.css') }}" />
    <link rel="stylesheet" href="{{ asset('guest/assets/material-symbols-outlined.css') }}" />
    <link rel="stylesheet" href="{{ asset('guest/assets/tabler-icons.css') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'system-ui', 'sans-serif']
                    },
                    colors: {
                        'oss-base-black': '#0F172A',
                        'oss-base-white': '#FFFFFF',
                        'oss-gray-25': '#F9FAFB',
                        'oss-gray-200': '#E5E7EB',
                        'oss-gray-500': '#6B7280',
                        'oss-blue-500': '#00479B',
                        'primary': '#8b1c13',
                        'primary-600': '#8b1c13',
                        'primary-700': '#74160f',
                    },
                    maxWidth: {
                        '1920': '1920px'
                    },
                },
            },
        };
    </script>
    @vite(['resources/js/app.js'])
    @inertiaHead
</head>
<body class="bg-gray-100 min-h-screen antialiased">
    @inertia
</body>
</html>

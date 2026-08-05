<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Event Receipt System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link rel="icon" type="image/png" href="{{ asset('images/binus4.png') }}">
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <!-- Background Full Layar Biru (Bukan lagi dibagi dua) -->
        <div class="min-h-screen relative bg-gradient-to-br from-blue-900 to-blue-700 flex flex-col justify-center items-center overflow-hidden p-4 sm:p-8">

            <!-- Ornamen Dekorasi Background (Tetap dipertahankan agar tidak polos) -->
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-blue-500 opacity-30 blur-3xl"></div>
            <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-orange-400 opacity-20 blur-3xl"></div>

            <!-- Wrapper Konten Utama (Tengah) -->
            <div class="relative z-10 w-full max-w-md flex flex-col items-center">

                <!-- Logo -->
                <div class="mb-6">
                    <img src="{{ asset('images/BINUSUNIVERSITYWhite.png') }}"
                        alt="Logo Binus"
                        class="w-80 sm:w-96 h-auto object-contain drop-shadow-lg"
                        style="max-width: 480px;">
                </div>

                <!-- Teks Judul -->
                <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2 text-center">
                    Event Receipt System
                </h1>
                <p class="text-blue-100 text-sm font-medium tracking-wide mb-8 text-center">
                    Bina Nusantara University
                </p>

                <!-- Card Form Login (Dipindah tepat di bawah judul) -->
                <div class="w-full px-8 py-10 bg-white shadow-2xl rounded-2xl border border-gray-100">

                    <div class="mb-6 text-center">
                        <h2 class="text-2xl font-bold text-gray-800">Selamat Datang</h2>
                        <p class="text-sm text-gray-500 mt-2">Silakan masuk ke akun Anda</p>
                    </div>

                    <!-- Tempat Form Login Breeze -->
                    {{ $slot }}
                </div>

                <!-- Footer Simpel -->
                <div class="mt-8 text-center text-sm text-blue-200">
                    &copy; {{ date('Y') }} IT BINUS Univ Bekasi. All rights reserved.
                </div>

            </div>
        </div>
    </body>
</html>

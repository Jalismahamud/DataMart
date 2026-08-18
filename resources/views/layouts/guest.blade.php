<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
            .glass-panel {
                background: rgba(17, 24, 39, 0.7);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .blob {
                animation: float 10s infinite;
            }
            .blob-2 {
                animation: float 12s infinite reverse;
            }
            @keyframes float {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
            /* Hide AdminLTE injected accessibility links */
            a[href="#main"], a[href="#navigation"] {
                display: none !important;
            }
        </style>
    </head>
    <body class="antialiased bg-gray-900 text-white relative overflow-hidden min-h-screen selection:bg-indigo-500 selection:text-white">
        <!-- Background Blobs -->
        <div class="absolute top-0 -left-4 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 blob-2" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 blob" style="animation-delay: 4s;"></div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10 p-4">
            <!-- Logo Section -->
            <div class="mb-8 transform transition hover:scale-105 duration-300">
                <a href="/" class="flex flex-col items-center gap-3 group block text-center">
                    <div class="w-16 h-16 bg-gradient-to-tr from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-all duration-300">
                        <svg class="w-8 h-8 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    @php
                        $siteName = \App\Models\SiteSetting::getValue('site_name', 'DadaGarments');
                    @endphp
                    <div class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-300 tracking-tight drop-shadow-sm">
                        {{ $siteName }}
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-8 py-10 glass-panel shadow-2xl rounded-3xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

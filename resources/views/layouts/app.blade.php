<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WarungDigi') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 text-slate-800">
    <div class="min-h-screen flex flex-col selection:bg-indigo-100 selection:text-indigo-700">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
            <div class="container mx-auto px-4 h-20 flex items-center justify-between max-w-6xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-100">
                        <span class="text-white text-xl font-black">W</span>
                    </div>
                    <h1 class="text-xl font-black text-slate-800 tracking-tight">Warung<span class="text-indigo-600">Digi</span></h1>
                </div>

                @if (Route::has('login'))
                <nav class="flex gap-4">
                    @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 rounded-xl font-bold text-sm bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-all">
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-xl font-bold text-sm bg-slate-900 text-white hover:bg-black transition-all shadow-lg hover:shadow-xl active:scale-95">
                        Login Admin
                    </a>
                    @endauth
                </nav>
                @endif
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-grow container mx-auto px-4 py-8 max-w-6xl">
            @yield('content')
        </main>

        <footer class="bg-white border-t border-slate-100 py-12 mt-12">
            <div class="container mx-auto px-4 text-center">
                <p class="text-slate-400 text-sm">© {{ date('Y') }} WarungDigi. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>

</html>
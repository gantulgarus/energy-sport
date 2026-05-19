<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Эрчим хүчний салбарын спортын их наадам 2026')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex flex-col font-sans antialiased">

    <nav class="bg-blue-900 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="Лого" class="h-10 object-contain">
                    @else
                        <div class="h-10 w-10 bg-yellow-400 rounded-full flex items-center justify-center text-blue-900 font-bold text-lg">⚡</div>
                    @endif
                    <div class="leading-tight">
                        <div class="font-bold text-sm sm:text-base">Эрчим хүчний салбар</div>
                        <div class="text-blue-300 text-xs">Спортын их наадам 2026</div>
                    </div>
                </a>
                <div class="hidden lg:flex items-center text-sm font-medium">
                    <a href="{{ route('home') }}" class="px-3 py-1 rounded hover:text-yellow-300 transition {{ request()->routeIs('home') ? 'text-yellow-300' : '' }}">Нүүр</a>
                    <a href="{{ route('standings') }}" class="px-3 py-1 rounded hover:text-yellow-300 transition {{ request()->routeIs('standings') ? 'text-yellow-300' : '' }}">🏆 Үр дүн</a>
                    <span class="w-px h-5 bg-blue-600 mx-2"></span>
                    @foreach(\App\Models\Sport::orderBy('sort_order')->get() as $s)
                        <a href="{{ route('sport.show', $s->slug) }}" class="flex items-center gap-1.5 px-3 py-1 rounded hover:bg-blue-800 hover:text-yellow-300 transition {{ request()->is('sport/'.$s->slug) ? 'bg-blue-800 text-yellow-300' : '' }}">
                            <x-sport-icon :sport="$s" class="w-5 h-5 brightness-0 invert" />{{ $s->name }}
                        </a>
                    @endforeach
                </div>
                <button onclick="document.getElementById('mob-menu').classList.toggle('hidden')" class="lg:hidden p-2 rounded hover:bg-blue-800">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        <div id="mob-menu" class="hidden lg:hidden border-t border-blue-800 bg-blue-900">
            <div class="px-4 py-2 space-y-1 text-sm">
                <a href="{{ route('home') }}" class="block py-2 hover:text-yellow-300">Нүүр</a>
                <a href="{{ route('standings') }}" class="block py-2 hover:text-yellow-300">🏆 Үр дүн</a>
                @foreach(\App\Models\Sport::orderBy('sort_order')->get() as $s)
                    <a href="{{ route('sport.show', $s->slug) }}" class="flex items-center gap-2 py-2 hover:text-yellow-300">
                        <x-sport-icon :sport="$s" class="w-5 h-5 brightness-0 invert" />{{ $s->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>

    <main class="flex-1">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 text-center text-sm">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-blue-900 text-blue-300 text-center text-xs py-4 mt-8">
        © 2026 Эрчим хүчний салбарын спортын их наадам · Улаанбаатар хот
    </footer>
</body>
</html>

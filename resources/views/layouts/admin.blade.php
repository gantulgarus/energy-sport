<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · @yield('title', 'Спортын их наадам')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen font-sans antialiased">

    <nav class="bg-blue-900 text-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-14">
            <a href="{{ route('admin.dashboard') }}" class="font-bold text-sm flex items-center gap-2">
                <span class="text-yellow-300">⚡</span> Admin панель
            </a>
            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition" target="_blank">Сайт харах ↗</a>
                <span class="text-blue-400">|</span>
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-red-300 transition">Гарах</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex gap-6">
        {{-- Sidebar --}}
        <aside class="w-48 shrink-0">
            <nav class="bg-white rounded-lg shadow p-3 space-y-1 text-sm sticky top-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-800 font-semibold' : 'text-gray-700' }}">
                    📊 Хяналтын самбар
                </a>
                <a href="{{ route('admin.teams.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('admin.teams.*') ? 'bg-blue-100 text-blue-800 font-semibold' : 'text-gray-700' }}">
                    🏢 Байгууллагууд
                </a>
                <a href="{{ route('admin.groups.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('admin.groups.*') ? 'bg-blue-100 text-blue-800 font-semibold' : 'text-gray-700' }}">
                    🏆 Хэсгийн хуваарь
                </a>
                <a href="{{ route('admin.results.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('admin.results.*') ? 'bg-blue-100 text-blue-800 font-semibold' : 'text-gray-700' }}">
                    🏅 Үр дүн оруулах
                </a>
                <a href="{{ route('admin.matches.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('admin.matches.*') ? 'bg-blue-100 text-blue-800 font-semibold' : 'text-gray-700' }}">
                    📅 Хуваарь
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 text-blue-800 font-semibold' : 'text-gray-700' }}">
                    👤 Хэрэглэгчид
                </a>
            </nav>
        </aside>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded text-sm">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded text-sm">
                    ✗ {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</body>
</html>

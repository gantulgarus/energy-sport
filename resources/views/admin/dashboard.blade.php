@extends('layouts.admin')

@section('title', 'Хяналтын самбар')

@section('content')
<h1 class="text-xl font-bold text-gray-800 mb-6">📊 Хяналтын самбар</h1>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow p-5 text-center">
        <div class="text-3xl font-bold text-blue-700">{{ $teamsCount }}</div>
        <div class="text-gray-500 text-sm mt-1">Бүртгэлтэй байгууллага</div>
        <a href="{{ route('admin.teams.index') }}" class="text-blue-600 text-xs hover:underline mt-2 inline-block">Удирдах →</a>
    </div>
    <div class="bg-white rounded-xl shadow p-5 text-center">
        <div class="text-3xl font-bold text-green-700">{{ $resultsCount }}</div>
        <div class="text-gray-500 text-sm mt-1">Бүртгэлтэй үр дүн</div>
        <a href="{{ route('admin.results.index') }}" class="text-blue-600 text-xs hover:underline mt-2 inline-block">Оруулах →</a>
    </div>
    <div class="bg-white rounded-xl shadow p-5 text-center">
        <div class="text-3xl font-bold text-yellow-600">6</div>
        <div class="text-gray-500 text-sm mt-1">Спортын төрөл</div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 text-center">
        <div class="text-3xl font-bold text-purple-700">{{ \App\Models\GameMatch::count() }}</div>
        <div class="text-gray-500 text-sm mt-1">Тоглолтын хуваарь</div>
        <a href="{{ route('admin.matches.index') }}" class="text-blue-600 text-xs hover:underline mt-2 inline-block">Удирдах →</a>
    </div>
</div>

@if(auth()->user()->is_admin)
<h2 class="text-lg font-semibold text-gray-700 mb-3">Спорт бүрийн үр дүн оруулах</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($sports as $sport)
    <a href="{{ route('admin.results.edit', $sport->slug) }}"
       class="bg-white rounded-xl shadow hover:shadow-md hover:bg-blue-50 transition p-4 flex items-center gap-3">
        <x-sport-icon :sport="$sport" class="w-9 h-9" />
        <div>
            <div class="font-semibold text-gray-800">{{ $sport->name }}</div>
            <div class="text-xs text-gray-500">{{ $sport->results_count }} үр дүн бүртгэгдсэн</div>
        </div>
        <span class="ml-auto text-blue-400">→</span>
    </a>
    @endforeach
</div>
@endif
@endsection

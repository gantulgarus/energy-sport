@extends('layouts.admin')

@section('title', 'Үр дүн оруулах')

@section('content')
<h1 class="text-xl font-bold text-gray-800 mb-6">🏅 Үр дүн оруулах</h1>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($sports as $sport)
    <a href="{{ route('admin.results.edit', $sport->slug) }}"
       class="bg-white rounded-xl shadow hover:shadow-md hover:bg-blue-50 transition p-5 flex items-center gap-4">
        <x-sport-icon :sport="$sport" class="w-10 h-10" />
        <div>
            <div class="font-semibold text-gray-800">{{ $sport->name }}</div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $sport->isMixed() ? 'Холимог баг' : 'Эрэгтэй / Эмэгтэй' }}</div>
        </div>
        <span class="ml-auto text-blue-400 text-lg">→</span>
    </a>
    @endforeach
</div>
@endsection

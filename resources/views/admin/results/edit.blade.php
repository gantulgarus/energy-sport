@extends('layouts.admin')

@section('title', $sport->name . ' · Үр дүн')

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.results.index') }}" class="text-gray-400 hover:text-gray-600">←</a>
    <x-sport-icon :sport="$sport" class="w-8 h-8" />
    <h1 class="text-xl font-bold text-gray-800">{{ $sport->name }} · Үр дүн оруулах</h1>
</div>

<form method="POST" action="{{ route('admin.results.update', $sport->slug) }}">
    @csrf

    @foreach($genders as $gender)
    @php
        $label = match($gender) { 'male' => '🔵 Эрэгтэй', 'female' => '🔴 Эмэгтэй', 'mixed' => '🟡 Холимог баг', default => $gender };
    @endphp
    <div class="bg-white rounded-xl shadow mb-6 overflow-hidden">
        <div class="bg-gray-800 text-white px-4 py-3 font-semibold">{{ $label }}</div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-gray-600">
                    <th class="px-4 py-2 text-left">Байгууллага</th>
                    <th class="px-4 py-2 text-center w-28">Байр</th>
                    <th class="px-4 py-2 text-center w-24">Оноо</th>
                    <th class="px-4 py-2 text-left">Тэмдэглэл</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($teams as $team)
                @php
                    $existing = $existingResults[$gender][$team->id] ?? null;
                    $place = $existing?->place;
                    $points = $existing?->points;
                    $notes = $existing?->notes;
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            @if($team->logo)
                                <img src="{{ asset('storage/' . $team->logo) }}" alt="" class="h-6 w-6 object-contain rounded">
                            @else
                                <div class="h-6 w-6 bg-blue-100 rounded flex items-center justify-center text-xs font-bold text-blue-700">{{ mb_substr($team->name, 0, 1) }}</div>
                            @endif
                            <span class="font-medium text-gray-700">{{ $team->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <input type="number" name="results[{{ $gender }}][{{ $team->id }}][place]"
                               value="{{ $place }}" min="1" max="100" placeholder="—"
                               class="w-full border border-gray-300 rounded px-2 py-1 text-center text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </td>
                    <td class="px-4 py-2 text-center text-blue-700 font-semibold">
                        <span id="pts-{{ $gender }}-{{ $team->id }}">{{ $points ? number_format($points, 1) : '—' }}</span>
                    </td>
                    <td class="px-4 py-2">
                        <input type="text" name="results[{{ $gender }}][{{ $team->id }}][notes]"
                               value="{{ $notes }}" placeholder="Тэмдэглэл..."
                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach

    <div class="flex gap-3">
        <button type="submit" class="bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm hover:bg-blue-800 transition font-semibold">
            💾 Хадгалах
        </button>
        <a href="{{ route('admin.results.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-gray-300 hover:bg-gray-50 transition">
            Цуцлах
        </a>
    </div>
</form>
@endsection

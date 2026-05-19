@extends('layouts.public')

@section('title', 'Нийт эрэмбэ · Спортын их наадам 2026')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">🏆 Нийт эрэмбэ</h1>
    <p class="text-gray-500 text-sm mb-6">Бүх байгууллагын нийлбэр оноогоор эрэмбэлсэн</p>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-blue-900 text-white text-left">
                    <th class="px-4 py-3 w-12">Байр</th>
                    <th class="px-4 py-3">Байгууллага</th>
                    @foreach($sports as $sport)
                        <th class="px-3 py-3 text-center whitespace-nowrap" title="{{ $sport->name }}"><x-sport-icon :sport="$sport" class="w-7 h-7 mx-auto brightness-0 invert" /></th>
                    @endforeach
                    <th class="px-4 py-3 text-center font-bold">Нийт</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($teams as $i => $team)
                <tr class="hover:bg-gray-50 {{ $i < 3 ? 'bg-yellow-50' : '' }}">
                    <td class="px-4 py-3 font-bold text-center">
                        @if($i === 0) 🥇
                        @elseif($i === 1) 🥈
                        @elseif($i === 2) 🥉
                        @else {{ $i + 1 }}
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            @if($team->logo)
                                <img src="{{ asset('storage/' . $team->logo) }}" alt="" class="h-7 w-7 object-contain rounded">
                            @else
                                <div class="h-7 w-7 bg-blue-100 rounded flex items-center justify-center text-xs font-bold text-blue-700">
                                    {{ mb_substr($team->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="font-medium text-gray-800">{{ $team->name }}</span>
                        </div>
                    </td>
                    @foreach($sports as $sport)
                        @php
                            $sportPoints = $team->results->where('sport_id', $sport->id)->sum('points');
                        @endphp
                        <td class="px-3 py-3 text-center text-gray-600">
                            {!! $sportPoints > 0 ? number_format($sportPoints, 1) : '<span class="text-gray-300">—</span>' !!}
                        </td>
                    @endforeach
                    <td class="px-4 py-3 text-center font-bold text-blue-800 text-base">
                        {{ number_format($team->total_points, 1) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $sports->count() + 3 }}" class="px-4 py-8 text-center text-gray-400">
                        Одоохондоо үр дүн байхгүй байна
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Sport links --}}
    <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        @foreach($sports as $sport)
        <a href="{{ route('sport.show', $sport->slug) }}"
           class="bg-white rounded-lg shadow hover:shadow-md hover:bg-blue-50 transition p-3 text-center text-sm">
            <div class="flex justify-center"><x-sport-icon :sport="$sport" class="w-8 h-8" /></div>
            <div class="font-medium text-gray-700 mt-1">{{ $sport->name }}</div>
        </a>
        @endforeach
    </div>
</div>
@endsection

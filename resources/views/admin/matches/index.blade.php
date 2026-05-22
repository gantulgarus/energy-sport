@extends('layouts.admin')

@section('title', 'Тоглолтын хуваарь')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-gray-800">📅 Тоглолтын хуваарь</h1>
    <a href="{{ route('admin.matches.create', ['sport' => $activeSport->slug]) }}"
       class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
        + {{ $activeSport->name }} нэмэх
    </a>
</div>


@php
    $genderOrder  = ['male', 'female', 'mixed'];
    $genderLabels = ['male' => '🔵 Эрэгтэй', 'female' => '🔴 Эмэгтэй', 'mixed' => '🟡 Холимог'];
@endphp

@if($matches->isEmpty())
    <div class="bg-white rounded-xl shadow p-10 text-center text-gray-400">
        {{ $activeSport->name }}-ийн тоглолт байхгүй байна.
        <a href="{{ route('admin.matches.create') }}" class="text-blue-600 underline ml-1">Нэмэх</a>
    </div>
@else
    @foreach($genderOrder as $gender)
        @if($matches->has($gender))
        <div class="mb-8">
            <div class="text-sm font-bold text-gray-600 mb-3">{{ $genderLabels[$gender] }}</div>

            @foreach($matches[$gender] as $date => $dayMatches)
            <div class="mb-4">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
                    {{ \Carbon\Carbon::parse($date)->isoFormat('YYYY оны MM-р сарын DD, dddd') }}
                </div>
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                            <tr>
                                <th class="px-4 py-2 text-left">Цаг</th>
                                <th class="px-4 py-2 text-left">Шат</th>
                                <th class="px-4 py-2 text-center">Баг 1</th>
                                <th class="px-4 py-2 text-center w-24">Оноо</th>
                                <th class="px-4 py-2 text-center">Баг 2</th>
                                <th class="px-4 py-2 text-left">Газар</th>
                                <th class="px-4 py-2 text-center">Төлөв</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($dayMatches as $match)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $match->scheduled_at->format('H:i') }}</td>
                                <td class="px-4 py-3 text-xs text-gray-400">{{ $match->round ?? '—' }}</td>
                                <td class="px-4 py-3 text-center font-medium {{ $match->isFinished() && $match->team1_score > $match->team2_score ? 'text-blue-700' : 'text-gray-700' }}">
                                    {{ $match->team1->short_name ?? $match->team1->name }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($match->isFinished())
                                        <span class="font-bold text-gray-800">{{ $match->team1_score }} – {{ $match->team2_score }}</span>
                                    @elseif($match->isLive())
                                        <span class="text-red-500 font-bold animate-pulse">● LIVE</span>
                                    @else
                                        <span class="text-gray-300">vs</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center font-medium {{ $match->isFinished() && $match->team2_score > $match->team1_score ? 'text-blue-700' : 'text-gray-700' }}">
                                    {{ $match->team2->short_name ?? $match->team2->name }}
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-400">{{ $match->venue ?? '—' }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($match->status === 'finished')
                                        <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">Дууссан</span>
                                    @elseif($match->status === 'live')
                                        <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full">Явагдаж байна</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">Болоогүй</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.matches.edit', $match) }}" class="text-blue-600 hover:underline text-xs mr-2">Засах</a>
                                    <form action="{{ route('admin.matches.destroy', $match) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Устгах уу?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:underline text-xs">Устгах</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    @endforeach
@endif
@endsection

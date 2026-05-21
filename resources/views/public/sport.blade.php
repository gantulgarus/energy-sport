@extends('layouts.public')

@section('title', $sport->name . ' · Спортын их наадам 2026')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6">
        <x-sport-icon :sport="$sport" class="w-10 h-10" />
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $sport->name }}</h1>
            <p class="text-gray-500 text-sm">{{ $sport->isMixed() ? 'Холимог баг' : 'Эрэгтэй / Эмэгтэй ангилал' }}</p>
        </div>
    </div>

    @if($results->isEmpty())
        <div class="bg-white rounded-xl shadow p-8 text-center text-gray-400">
            Одоохондоо үр дүн бүртгэгдээгүй байна
        </div>
    @else
        @php
            $genderLabels = ['male' => '🔵 Эрэгтэй', 'female' => '🔴 Эмэгтэй', 'mixed' => '🟡 Холимог'];
        @endphp

        @foreach($results as $gender => $genderResults)
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-3">{{ $genderLabels[$gender] ?? $gender }}</h2>
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-800 text-white text-left">
                            <th class="px-4 py-3 w-16">Байр</th>
                            <th class="px-4 py-3">Байгууллага</th>
                            <th class="px-4 py-3 text-right">Оноо</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($genderResults->sortBy('place') as $result)
                        @if($result->place)
                        <tr class="hover:bg-gray-50 {{ $result->place <= 3 ? 'bg-yellow-50' : '' }}">
                            <td class="px-4 py-3 font-bold text-center">
                                @if($result->place === 1) 🥇
                                @elseif($result->place === 2) 🥈
                                @elseif($result->place === 3) 🥉
                                @else {{ $result->place }}
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @if($result->team->logo)
                                        <img src="{{ asset('storage/' . $result->team->logo) }}" alt="" class="h-7 w-7 object-contain rounded">
                                    @else
                                        <div class="h-7 w-7 bg-blue-100 rounded flex items-center justify-center text-xs font-bold text-blue-700">
                                            {{ mb_substr($result->team->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="font-medium">{{ $result->team->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-blue-700">
                                {{ number_format($result->points, 1) }}
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    @endif

    {{-- Groups --}}
    @if($groups->isNotEmpty())
    <div class="mt-10">
        <h2 class="text-lg font-bold text-gray-800 mb-4">🏆 Хэсгийн хуваарилалт</h2>
        @php
            $genderLabels = ['male' => '🔵 Эрэгтэй', 'female' => '🔴 Эмэгтэй', 'mixed' => '🟡 Холимог'];
            $genderOrder  = ['male','female','mixed'];
        @endphp
        @foreach($genderOrder as $gender)
            @if($groups->has($gender))
            <div class="mb-6">
                @if(!$sport->isMixed())
                <div class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-3">
                    {{ $genderLabels[$gender] }}
                </div>
                @endif
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach($groups[$gender] as $groupName => $members)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-blue-700 text-white text-xs font-bold px-3 py-1.5 tracking-widest uppercase">
                            {{ $groupName }} Хэсэг
                        </div>
                        <ul class="divide-y divide-gray-50">
                            @foreach($members as $ga)
                            <li class="px-3 py-2 flex items-center gap-2">
                                <span class="text-xs text-gray-400 w-4 shrink-0">{{ $ga->order_num }}</span>
                                <span class="text-xs text-gray-700 leading-tight">{{ $ga->team->name }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach
    </div>
    @endif

    {{-- Matches --}}
    <div class="mt-10">
        <h2 class="text-lg font-bold text-gray-800 mb-4">📅 Тоглолтын хуваарь</h2>

        @if($matches->isEmpty())
            <div class="bg-white rounded-xl shadow p-6 text-center text-gray-400 text-sm">
                Тоглолт бүртгэгдээгүй байна
            </div>
        @else
            @php
                $genderOrder  = ['male', 'female', 'mixed'];
                $genderLabels = ['male' => '🔵 Эрэгтэй', 'female' => '🔴 Эмэгтэй', 'mixed' => '🟡 Холимог'];
            @endphp
            @foreach($matches as $date => $dayMatches)
            <div class="mb-6">
                <div class="text-xs font-bold text-blue-700 bg-blue-50 border border-blue-100 px-3 py-1 rounded-full inline-block mb-3 uppercase tracking-wide">
                    {{ \Carbon\Carbon::parse($date)->isoFormat('MM/DD · dddd') }}
                </div>
                @php $grouped = $dayMatches->groupBy('gender'); @endphp
                @foreach($genderOrder as $gender)
                    @if($grouped->has($gender))
                    <div class="mb-4">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 pl-1">
                            {{ $genderLabels[$gender] }}
                        </div>
                        <div class="bg-white rounded-xl shadow overflow-hidden divide-y divide-gray-100">
                            @foreach($grouped[$gender] as $m)
                            <div class="flex items-center px-4 py-3 gap-3 hover:bg-gray-50 transition">
                                <span class="text-xs text-gray-400 w-10 shrink-0">{{ $m->scheduled_at->format('H:i') }}</span>
                                @if($m->round)
                                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded shrink-0">{{ $m->round }}</span>
                                @endif

                                <span class="flex-1 text-sm font-semibold text-right truncate
                                    {{ $m->isFinished() && $m->team1_score > $m->team2_score ? 'text-blue-700' : 'text-gray-800' }}">
                                    {{ $m->team1->short_name ?? $m->team1->name }}
                                </span>

                                <div class="text-center w-20 shrink-0">
                                    @if($m->isFinished())
                                        <span class="font-black text-gray-800">{{ $m->team1_score }} – {{ $m->team2_score }}</span>
                                    @elseif($m->isLive())
                                        <span class="text-red-500 font-bold text-xs animate-pulse">● LIVE</span>
                                    @else
                                        <span class="text-gray-300 font-bold text-sm">vs</span>
                                    @endif
                                </div>

                                <span class="flex-1 text-sm font-semibold truncate
                                    {{ $m->isFinished() && $m->team2_score > $m->team1_score ? 'text-blue-700' : 'text-gray-800' }}">
                                    {{ $m->team2->short_name ?? $m->team2->name }}
                                </span>

                                @if($m->isFinished())
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full shrink-0">Дууссан</span>
                                @elseif($m->isLive())
                                    <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full shrink-0">Live</span>
                                @else
                                    <span class="text-xs bg-gray-100 text-gray-400 px-2 py-0.5 rounded-full shrink-0">Болоогүй</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            @endforeach
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('standings') }}" class="text-blue-600 hover:underline text-sm">← Үр дүн рүү буцах</a>
    </div>
</div>
@endsection

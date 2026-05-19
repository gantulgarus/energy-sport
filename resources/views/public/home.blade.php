@extends('layouts.public')

@section('title', 'Нүүр · Эрчим хүчний салбарын спортын их наадам 2026')

@section('content')

{{-- Hero --}}
<div class="relative overflow-hidden text-white" style="background-image: url('{{ asset('images/bg2.png') }}'); background-size: cover; background-position: center center; min-height: 560px;">

    {{-- Dark gradient overlay --}}
    <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(11,30,74,0.82) 0%, rgba(13,45,107,0.78) 55%, rgba(26,64,128,0.72) 100%);"></div>

    {{-- Dot pattern --}}
    <div class="absolute inset-0 opacity-[0.06]"
         style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 32px 32px;"></div>

    {{-- Top glow --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-64 opacity-20 pointer-events-none"
         style="background: radial-gradient(ellipse at center, #facc15 0%, transparent 70%);"></div>

    {{-- Content --}}
    <div class="relative max-w-5xl mx-auto px-4 pt-14 pb-6 text-center">

        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 border border-yellow-400/40 bg-yellow-400/10 text-yellow-300 text-xs font-bold px-5 py-1.5 rounded-full mb-6 tracking-widest uppercase">
            ⚡ Эрчим хүчний салбар &nbsp;·&nbsp; 2026
        </div>

        <h1 class="text-4xl sm:text-6xl font-black leading-tight mb-4 tracking-tight"
            style="text-shadow: 0 2px 24px rgba(0,0,0,0.5);">
            Спортын их наадам
        </h1>

        <p class="text-blue-200 text-sm sm:text-base mb-10 max-w-lg mx-auto leading-relaxed">
            Эрчим хүчний салбарын байгууллагуудын дунд зохион байгуулагдах
            <span class="text-yellow-300 font-semibold">зургаан төрлийн</span> спортын тэмцээн
        </p>

        {{-- Stats row --}}
        <div class="flex flex-wrap justify-center gap-3 sm:gap-4 mb-10">
            <div class="flex flex-col items-center bg-white/10 backdrop-blur-sm border border-white/15 rounded-2xl px-6 py-3 min-w-[90px]">
                <span class="text-2xl font-black text-yellow-300">{{ $teamsCount ?: '30+' }}</span>
                <span class="text-blue-300 text-xs mt-0.5">Байгууллага</span>
            </div>
            <div class="flex flex-col items-center bg-white/10 backdrop-blur-sm border border-white/15 rounded-2xl px-6 py-3 min-w-[90px]">
                <span class="text-2xl font-black text-yellow-300">6</span>
                <span class="text-blue-300 text-xs mt-0.5">Спортын төрөл</span>
            </div>
            <div class="flex flex-col items-center bg-white/10 backdrop-blur-sm border border-white/15 rounded-2xl px-6 py-3 min-w-[90px]">
                <span class="text-2xl font-black text-yellow-300">5</span>
                <span class="text-blue-300 text-xs mt-0.5">Өдөр</span>
            </div>
            <div class="flex flex-col items-center bg-white/10 backdrop-blur-sm border border-white/15 rounded-2xl px-6 py-3 min-w-[90px]">
                <span class="text-lg font-black text-yellow-300">Улаанбаатар</span>
                <span class="text-blue-300 text-xs mt-0.5">📍 Хот</span>
            </div>
        </div>

        {{-- Date + CTA --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mb-10">
            <span class="flex items-center gap-2 bg-white/10 border border-white/20 text-white text-sm px-5 py-2.5 rounded-xl">
                📅 2026 оны <strong>5-р сарын 22 – 27</strong>
            </span>
            <a href="{{ route('standings') }}"
               class="flex items-center gap-2 bg-yellow-400 hover:bg-yellow-300 text-blue-900 font-bold text-sm px-7 py-2.5 rounded-xl transition-all shadow-lg hover:-translate-y-0.5">
                🏆 Үр дүн харах
            </a>
            <a href="#sports"
               class="flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/25 text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition-all">
                Төрлүүд ↓
            </a>
        </div>
    </div>

</div>

{{-- Sports grid --}}
<div id="sports" class="bg-gray-50 py-14">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-8">
            <span class="text-xs font-bold tracking-widest text-blue-600 uppercase">Тэмцээний</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-1">Спортын төрлүүд</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($sports as $sport)
            <a href="{{ route('sport.show', $sport->slug) }}"
               class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-blue-300 hover:shadow-md hover:-translate-y-1 transition-all duration-200 p-5 text-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                <div class="relative mb-3 flex justify-center"><x-sport-icon :sport="$sport" class="w-12 h-12" /></div>
                <div class="relative font-bold text-gray-800 text-sm leading-tight group-hover:text-blue-700 transition-colors">{{ $sport->name }}</div>
                <div class="relative text-xs text-gray-400 mt-1.5 font-medium">
                    {{ $sport->isMixed() ? 'Холимог' : 'Эр / Эм' }}
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Schedule --}}
<div class="bg-white border-t border-gray-100">
    <div class="max-w-5xl mx-auto px-4 py-14">
        <div class="text-center mb-8">
            <span class="text-xs font-bold tracking-widest text-blue-600 uppercase">Тэмцээний</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-1">Хуваарь</h2>
        </div>

        @if($schedule->isEmpty())
            <p class="text-center text-gray-400 text-sm">Тоглолтын хуваарь оруулагдаагүй байна.</p>
        @else
            @php
                $genderOrder  = ['male', 'female', 'mixed'];
                $genderLabels = ['male' => '🔵 Эрэгтэй', 'female' => '🔴 Эмэгтэй', 'mixed' => '🟡 Холимог'];
            @endphp
            @foreach($schedule as $date => $dayMatches)
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-bold text-blue-700 bg-blue-50 border border-blue-100 px-3 py-1 rounded-full uppercase tracking-wide">
                        {{ \Carbon\Carbon::parse($date)->isoFormat('MM/DD · dddd') }}
                    </span>
                </div>
                @php $grouped = $dayMatches->groupBy('gender'); @endphp
                @foreach($genderOrder as $gender)
                    @if($grouped->has($gender))
                    <div class="mb-5">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 pl-1">
                            {{ $genderLabels[$gender] }}
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($grouped[$gender] as $m)
                            <div class="bg-gray-50 hover:bg-blue-50 border border-gray-100 hover:border-blue-200 rounded-2xl p-4 transition-all">
                                {{-- Header: спорт + цаг --}}
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <x-sport-icon :sport="$m->sport" class="w-6 h-6" />
                                        <span class="text-xs font-semibold text-gray-500">{{ $m->sport->name }}</span>
                                        @if($m->round)
                                            <span class="text-xs text-gray-400">· {{ $m->round }}</span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-blue-600 font-semibold">{{ $m->scheduled_at->format('H:i') }}</span>
                                </div>

                                {{-- Teams + Score --}}
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-sm font-bold flex-1 truncate text-right
                                        {{ $m->isFinished() && $m->team1_score > $m->team2_score ? 'text-blue-700' : 'text-gray-800' }}">
                                        {{ $m->team1->short_name ?? $m->team1->name }}
                                    </span>
                                    <div class="text-center w-16 shrink-0">
                                        @if($m->isFinished())
                                            <span class="font-black text-gray-800 text-base">{{ $m->team1_score }}–{{ $m->team2_score }}</span>
                                        @elseif($m->isLive())
                                            <span class="text-red-500 font-bold text-xs animate-pulse">● LIVE</span>
                                        @else
                                            <span class="text-gray-300 font-bold">vs</span>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold flex-1 truncate
                                        {{ $m->isFinished() && $m->team2_score > $m->team1_score ? 'text-blue-700' : 'text-gray-800' }}">
                                        {{ $m->team2->short_name ?? $m->team2->name }}
                                    </span>
                                </div>

                                {{-- Venue --}}
                                @if($m->venue)
                                <div class="text-xs text-gray-400 mt-2 text-center">📍 {{ $m->venue }}</div>
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
</div>

@endsection

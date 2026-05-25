@extends('layouts.admin')

@section('title', isset($match) ? 'Тоглолт засах' : 'Тоглолт нэмэх')

@section('content')
<div class="max-w-2xl">
    @php
        $sport = isset($match) ? $match->sport : $activeSport;
    @endphp
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.matches.index', ['sport' => $sport->slug]) }}" class="text-gray-400 hover:text-gray-600">←</a>
        <h1 class="text-xl font-bold text-gray-800">
            {{ $sport->icon }} {{ $sport->name }} — {{ isset($match) ? 'Тоглолт засах' : 'Тоглолт нэмэх' }}
        </h1>
    </div>

    <form method="POST"
          action="{{ isset($match) ? route('admin.matches.update', $match) : route('admin.matches.store') }}"
          class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf
        @if(isset($match)) @method('PUT') @endif

        <input type="hidden" name="sport_id" value="{{ $sport->id }}">

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Спорт</label>
                <div class="w-full border border-gray-100 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700">
                    {{ $sport->icon }} {{ $sport->name }}
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Ангилал</label>
                <select name="gender" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" required>
                    <option value="male"   {{ old('gender', $match->gender ?? '') === 'male'   ? 'selected' : '' }}>Эрэгтэй</option>
                    <option value="female" {{ old('gender', $match->gender ?? '') === 'female' ? 'selected' : '' }}>Эмэгтэй</option>
                    <option value="mixed"  {{ old('gender', $match->gender ?? '') === 'mixed'  ? 'selected' : '' }}>Холимог</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Баг 1</label>
                <select name="team1_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">— Сонгох —</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ old('team1_id', $match->team1_id ?? '') == $team->id ? 'selected' : '' }}>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Баг 2</label>
                <select name="team2_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">— Сонгох —</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ old('team2_id', $match->team2_id ?? '') == $team->id ? 'selected' : '' }}>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Огноо</label>
                <input type="date" name="scheduled_date"
                       value="{{ old('scheduled_date', isset($match) && $match->scheduled_at ? $match->scheduled_at->format('Y-m-d') : '') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Цаг <span class="text-gray-400 font-normal">(заавал биш)</span></label>
                <select name="scheduled_time" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <option value="">— сонгох —</option>
                    @php
                        $selectedTime = old('scheduled_time', isset($match) && $match->scheduled_at ? $match->scheduled_at->format('H:i') : '');
                    @endphp
                    @for($h = 7; $h <= 22; $h++)
                        @foreach(['00','30'] as $min)
                            @php $val = sprintf('%02d:%s', $h, $min); @endphp
                            <option value="{{ $val }}" {{ $selectedTime === $val ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    @endfor
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Шат / Round</label>
                <input type="text" name="round" value="{{ old('round', $match->round ?? '') }}"
                       placeholder="Бүлгийн шат, Финал…"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Тоглох газар</label>
            <input type="text" name="venue" value="{{ old('venue', $match->venue ?? '') }}"
                   placeholder="Улаанбаатар спортын ордон…"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="border-t pt-4">
            <div class="text-xs font-semibold text-gray-600 mb-3">Үр дүн (тоглолт дууссаны дараа бөглөх)</div>
            <div class="grid grid-cols-3 gap-4 items-center">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Баг 1 оноо</label>
                    <input type="text" name="team1_score" value="{{ old('team1_score', $match->team1_score ?? '') }}"
                           placeholder="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-center">
                </div>
                <div class="text-center text-gray-400 font-bold mt-4">–</div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Баг 2 оноо</label>
                    <input type="text" name="team2_score" value="{{ old('team2_score', $match->team2_score ?? '') }}"
                           placeholder="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-center">
                </div>
            </div>
            <div class="mt-3">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Төлөв</label>
                <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <option value="scheduled" {{ old('status', $match->status ?? 'scheduled') === 'scheduled' ? 'selected' : '' }}>Болоогүй</option>
                    <option value="live"      {{ old('status', $match->status ?? '') === 'live'      ? 'selected' : '' }}>Явагдаж байна</option>
                    <option value="finished"  {{ old('status', $match->status ?? '') === 'finished'  ? 'selected' : '' }}>Дууссан</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Тэмдэглэл</label>
            <textarea name="notes" rows="2" placeholder="Нэмэлт мэдээлэл…"
                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">{{ old('notes', $match->notes ?? '') }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white font-semibold px-6 py-2 rounded-lg text-sm transition">
                {{ isset($match) ? 'Хадгалах' : 'Нэмэх' }}
            </button>
            <a href="{{ route('admin.matches.index', ['sport' => $sport->slug]) }}" class="text-gray-500 hover:text-gray-700 text-sm px-4 py-2">Цуцлах</a>
        </div>
    </form>
</div>
@endsection

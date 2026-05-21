@extends('layouts.admin')

@section('title', 'Хэсгийн хуваарилалт')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-gray-800">🏆 Хэсгийн хуваарилалт</h1>
</div>

{{-- Sport tabs --}}
<div class="flex flex-wrap gap-2 mb-6">
    @foreach($sports as $sport)
        <a href="{{ route('admin.groups.index', ['sport' => $sport->slug]) }}"
           class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold transition
               {{ $activeSport->id === $sport->id
                   ? 'bg-blue-700 text-white shadow'
                   : 'bg-white text-gray-600 border border-gray-200 hover:border-blue-300 hover:text-blue-700' }}">
            {{ $sport->icon }} {{ $sport->name }}
        </a>
    @endforeach
</div>

@php
    $genderLabels = ['male' => '🔵 Эрэгтэй', 'female' => '🔴 Эмэгтэй', 'mixed' => '🟡 Холимог'];
    $groupOptions = ['A','B','C','D','E','F','G','H'];
@endphp

{{-- Gender sections --}}
@foreach($genders as $gender)
<div class="mb-10">
    @if(count($genders) > 1)
    <div class="text-sm font-bold text-gray-600 mb-4">{{ $genderLabels[$gender] }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Existing groups --}}
        @if($groups->has($gender))
            @foreach($groups[$gender] as $groupName => $members)
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="flex items-center justify-between bg-blue-700 text-white px-4 py-2">
                    <span class="font-bold tracking-widest text-sm">{{ $groupName }} ХЭСЭГ</span>
                    <span class="text-xs text-blue-200">{{ $members->count() }} баг</span>
                </div>
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-100">
                        @foreach($members as $ga)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2.5 text-gray-400 text-xs w-8 text-center">{{ $ga->order_num }}</td>
                            <td class="px-3 py-2.5 text-gray-800">{{ $ga->team->name }}</td>
                            <td class="px-3 py-2.5 w-28">
                                {{-- Inline хэсэг солих --}}
                                <form action="{{ route('admin.groups.update', $ga) }}" method="POST" class="flex gap-1">
                                    @csrf @method('PATCH')
                                    <select name="group_name"
                                        class="border border-gray-200 rounded text-xs px-1 py-1 w-14"
                                        onchange="this.form.submit()">
                                        @foreach($groupOptions as $g)
                                            <option value="{{ $g }}" {{ $ga->group_name === $g ? 'selected' : '' }}>{{ $g }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="order_num" value="{{ $ga->order_num }}">
                                </form>
                            </td>
                            <td class="px-3 py-2.5 text-right w-16">
                                <form action="{{ route('admin.groups.destroy', $ga) }}" method="POST"
                                      onsubmit="return confirm('Хэсгээс хасах уу?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 text-xs">Хасах</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
        @else
            <div class="col-span-2 text-gray-400 text-sm text-center py-6 bg-white rounded-xl shadow">
                {{ $activeSport->name }} — {{ $genderLabels[$gender] }}-д бүртгэл байхгүй байна.
            </div>
        @endif
    </div>

    {{-- Нэмэх форм --}}
    <div class="bg-white rounded-xl shadow p-5">
        <h3 class="text-sm font-bold text-gray-700 mb-4">+ Баг нэмэх</h3>
        <form action="{{ route('admin.groups.store') }}" method="POST"
              class="flex flex-wrap gap-3 items-end">
            @csrf
            <input type="hidden" name="sport_id" value="{{ $activeSport->id }}">
            <input type="hidden" name="gender"   value="{{ $gender }}">

            <div>
                <label class="block text-xs text-gray-500 mb-1">Хэсэг</label>
                <select name="group_name" required
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    @foreach($groupOptions as $g)
                        <option value="{{ $g }}">{{ $g }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-48">
                <label class="block text-xs text-gray-500 mb-1">Байгууллага</label>
                <select name="team_id" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <option value="">— сонгох —</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-20">
                <label class="block text-xs text-gray-500 mb-1">Дугаар</label>
                <input type="number" name="order_num" value="1" min="1" max="10"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>

            <button type="submit"
                class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold px-5 py-2 rounded-lg transition">
                Нэмэх
            </button>
        </form>
    </div>
</div>
@endforeach
@endsection

@extends('layouts.admin')

@section('title', 'Байгууллагууд')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-gray-800">🏢 Байгууллагууд</h1>
    <a href="{{ route('admin.teams.create') }}" class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-800 transition">
        + Нэмэх
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-left">
                <th class="px-4 py-3">#</th>
                <th class="px-4 py-3">Лого</th>
                <th class="px-4 py-3">Нэр</th>
                <th class="px-4 py-3">Товч нэр</th>
                <th class="px-4 py-3 text-center">Идэвхтэй</th>
                <th class="px-4 py-3 text-right">Үйлдэл</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($teams as $team)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-400">{{ $team->id }}</td>
                <td class="px-4 py-3">
                    @if($team->logo)
                        <img src="{{ asset('storage/' . $team->logo) }}" alt="" class="h-8 w-8 object-contain rounded">
                    @else
                        <div class="h-8 w-8 bg-blue-100 rounded flex items-center justify-center text-xs font-bold text-blue-700">
                            {{ mb_substr($team->name, 0, 1) }}
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $team->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $team->short_name ?? '—' }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="{{ $team->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }} text-xs px-2 py-0.5 rounded-full">
                        {{ $team->is_active ? 'Тийм' : 'Үгүй' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.teams.edit', $team) }}" class="text-blue-600 hover:underline">Засах</a>
                    <form method="POST" action="{{ route('admin.teams.destroy', $team) }}" class="inline"
                          onsubmit="return confirm('{{ $team->name }} устгах уу?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Устгах</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-400">Байгууллага байхгүй байна</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $teams->links() }}
    </div>
</div>
@endsection

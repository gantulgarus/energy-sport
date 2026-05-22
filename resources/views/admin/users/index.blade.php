@extends('layouts.admin')

@section('title', 'Хэрэглэгчид')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-gray-800">👤 Хэрэглэгчид</h1>
    <a href="{{ route('admin.users.create') }}"
       class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
        + Нэмэх
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-800 text-white text-left">
                <th class="px-4 py-3">Нэр</th>
                <th class="px-4 py-3">И-мэйл</th>
                <th class="px-4 py-3">Эрх</th>
                <th class="px-4 py-3">Бүртгэсэн огноо</th>
                <th class="px-4 py-3 w-28"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">
                    {{ $user->name }}
                    @if($user->id === auth()->id())
                        <span class="text-xs text-blue-500 ml-1">(та)</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                <td class="px-4 py-3">
                    @if($user->is_admin)
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-medium">Супер админ</span>
                    @elseif($user->sport)
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">{{ $user->sport->icon }} {{ $user->sport->name }}</span>
                    @else
                        <span class="text-xs text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-400 text-xs">{{ $user->created_at->format('Y-m-d') }}</td>
                <td class="px-4 py-3 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="text-xs text-blue-600 hover:underline">Засах</a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('Устгах уу?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-500 hover:underline">Устгах</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

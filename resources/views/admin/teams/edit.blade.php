@extends('layouts.admin')

@section('title', 'Байгууллага засах')

@section('content')
<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.teams.index') }}" class="text-gray-400 hover:text-gray-600">←</a>
        <h1 class="text-xl font-bold text-gray-800">Байгууллага засах</h1>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.teams.update', $team) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Байгууллагын нэр <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $team->name) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Товч нэр</label>
                <input type="text" name="short_name" value="{{ old('short_name', $team->short_name) }}" maxlength="50"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Лого</label>
                @if($team->logo)
                    <div class="mb-2 flex items-center gap-2">
                        <img src="{{ asset('storage/' . $team->logo) }}" alt="" class="h-12 w-12 object-contain rounded border">
                        <span class="text-xs text-gray-500">Одоогийн лого</span>
                    </div>
                @endif
                <input type="file" name="logo" accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $team->is_active ? 'checked' : '' }} class="rounded">
                <label for="is_active" class="text-sm text-gray-700">Идэвхтэй</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-700 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-800 transition">
                    Шинэчлэх
                </button>
                <a href="{{ route('admin.teams.index') }}" class="px-5 py-2 rounded-lg text-sm border border-gray-300 hover:bg-gray-50 transition">
                    Цуцлах
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

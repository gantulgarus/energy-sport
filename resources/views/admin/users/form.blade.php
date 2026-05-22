@extends('layouts.admin')

@section('title', $user->exists ? 'Хэрэглэгч засах' : 'Хэрэглэгч нэмэх')

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">← Буцах</a>
    <h1 class="text-xl font-bold text-gray-800">
        {{ $user->exists ? 'Хэрэглэгч засах' : 'Шинэ хэрэглэгч' }}
    </h1>
</div>

<div class="bg-white rounded-xl shadow p-6 max-w-lg">
    <form action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}"
          method="POST" class="space-y-4">
        @csrf
        @if($user->exists) @method('PATCH') @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Нэр</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">И-мэйл</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Нууц үг {{ $user->exists ? '(хоосон үлдээвэл өөрчлөхгүй)' : '' }}
            </label>
            <input type="password" name="password" {{ $user->exists ? '' : 'required' }}
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Нууц үг давтах</label>
            <input type="password" name="password_confirmation"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="bg-blue-700 hover:bg-blue-800 text-white font-semibold px-6 py-2 rounded-lg text-sm transition">
                {{ $user->exists ? 'Хадгалах' : 'Нэмэх' }}
            </button>
        </div>
    </form>
</div>
@endsection

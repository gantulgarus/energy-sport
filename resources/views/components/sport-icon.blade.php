@props(['sport' => null, 'slug' => null, 'class' => 'w-12 h-12'])

@php
    $map = [
        'basketball'  => '004-basketball-player-scoring.png',
        'volleyball'  => '005-volleyball.png',
        'table-tennis'=> '003-table-tennis.png',
        'chess'       => '007-queen.png',
        'esports'     => 'icons8-counter-strike.svg',
        'tug-of-war'  => '002-tug-of-war-1.png',
    ];
    $key  = $sport?->slug ?? $slug;
    $file = $map[$key] ?? null;
    $url  = $file && file_exists(public_path("images/sports/{$file}"))
        ? asset("images/sports/{$file}")
        : null;
    $alt  = $sport?->name ?? $key;
@endphp

@if($url)
    <img src="{{ $url }}" alt="{{ $alt }}" class="{{ $class }} object-contain">
@else
    <span class="{{ $class }} flex items-center justify-center text-4xl">{{ $sport?->icon ?? '🏅' }}</span>
@endif

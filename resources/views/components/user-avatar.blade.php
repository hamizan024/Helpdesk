@props(['user', 'size' => 36])

@php
    $avatarUrl = $user->getAvatarUrl();
    $initial   = strtoupper(substr($user->name, 0, 1));
    $fontSize  = (int) round($size * 0.4);
    $baseStyle = "width:{$size}px;height:{$size}px;border-radius:50%;flex-shrink:0;";
@endphp

@if($avatarUrl)
    <img src="{{ $avatarUrl }}"
         alt="{{ $user->name }}"
         style="{{ $baseStyle }} object-fit:cover;"
         {{ $attributes }}>
@else
    <div style="{{ $baseStyle }} background:linear-gradient(195deg,#EC407A,#D81B60);display:flex;align-items:center;justify-content:center;font-size:{{ $fontSize }}px;font-weight:700;color:#fff;"
         {{ $attributes }}>{{ $initial }}</div>
@endif
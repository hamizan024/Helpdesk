@props(['value'])

@php
    $map = [
        'High'   => ['class' => 'bg-gradient-danger',   'label' => 'High'],
        'Medium' => ['class' => 'bg-gradient-warning',  'label' => 'Medium'],
        'Low'    => ['class' => 'bg-gradient-secondary','label' => 'Low'],
    ];
    $item = $map[$value] ?? ['class' => 'bg-gradient-secondary', 'label' => $value];
@endphp

<span class="badge {{ $item['class'] }}">{{ $item['label'] }}</span>

@props(['value'])

@php
    $map = [
        'Open'        => ['class' => 'bg-gradient-primary', 'label' => 'Open'],
        'In Progress' => ['class' => 'bg-gradient-info',    'label' => 'In Progress'],
        'Closed'      => ['class' => 'bg-gradient-success', 'label' => 'Closed'],
    ];
    $item = $map[$value] ?? ['class' => 'bg-gradient-secondary', 'label' => $value];
@endphp

<span class="badge {{ $item['class'] }}">{{ $item['label'] }}</span>

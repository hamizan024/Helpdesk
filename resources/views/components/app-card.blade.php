{{--
    Dark-header card used throughout the Bootstrap dashboard layout.

    Props:
        title  – heading text in the card header (string)
        icon   – optional Material Icon name shown before the title
        badge  – optional count badge appended to the title (null = hidden)
        noPad  – true  → card-body p-0  (tables)
                 false → card-body p-4  (forms, details)

    Named slots:
        actions – content placed on the right side of the header (buttons, links)
--}}
@props([
    'title'  => '',
    'icon'   => null,
    'badge'  => null,
    'noPad'  => false,
])

<div {{ $attributes->merge(['class' => 'card']) }}>
    <div class="card-header py-3 px-4"
         style="background: linear-gradient(195deg, #42424a, #191919); border-radius: 12px 12px 0 0;">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                @if($icon)
                    <span class="material-icons-round text-white" style="font-size:1.1rem;">{{ $icon }}</span>
                @endif
                <h6 class="text-white mb-0 fw-bold">
                    {{ $title }}
                    @if($badge !== null)
                        <span class="badge ms-1" style="background:rgba(255,255,255,0.2); font-size:0.7rem;">{{ $badge }}</span>
                    @endif
                </h6>
            </div>
            @isset($actions)
                {{ $actions }}
            @endisset
        </div>
    </div>
    <div class="{{ $noPad ? 'card-body p-0' : 'card-body p-4' }}">
        {{ $slot }}
    </div>
</div>

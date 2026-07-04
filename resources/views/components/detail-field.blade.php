{{--
    A labelled detail field used on record-detail pages.
    Renders a small uppercase label above the slot content.

    Props:
        label – the field label text
--}}
@props(['label'])

<div {{ $attributes }}>
    <p class="detail-label">{{ $label }}</p>
    <div>{{ $slot }}</div>
</div>

{{--
    Bootstrap dismissible alert.

    Props:
        type        – Bootstrap color context: danger | success | warning | info
        dismissible – true (default) adds the close button and fade classes
--}}
@props([
    'type'        => 'danger',
    'dismissible' => true,
])

<div {{ $attributes->merge(['class' => "alert alert-{$type}" . ($dismissible ? ' alert-dismissible fade show' : '')]) }}
     role="alert">
    {{ $slot }}
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>

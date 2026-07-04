{{--
    Bootstrap form group: label + select + inline validation error.
    Place <option> elements in the default slot.

    Props:
        label – visible label text
        name  – select name attribute (also used for id and error lookup)
--}}
@props([
    'label',
    'name',
])

@php
    $hasError     = $errors->has($name);
    $errorMessage = $errors->first($name);
@endphp

<div class="mb-3">
    <label class="form-label" for="{{ $name }}">{{ $label }}</label>
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-select' . ($hasError ? ' is-invalid' : '')]) }}
    >
        {{ $slot }}
    </select>
    @if($hasError)
        <div class="invalid-feedback">{{ $errorMessage }}</div>
    @endif
</div>

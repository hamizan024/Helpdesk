{{--
    Bootstrap form group: label + textarea + inline validation error.

    Props:
        label       – visible label text
        name        – textarea name attribute (also used for id and error lookup)
        rows        – number of visible rows (default: 5)
        placeholder – placeholder text
        value       – current field value (use old() or model value)
--}}
@props([
    'label',
    'name',
    'rows'        => 5,
    'placeholder' => '',
    'value'       => '',
])

@php
    $hasError     = $errors->has($name);
    $errorMessage = $errors->first($name);
@endphp

<div class="mb-3">
    <label class="form-label" for="{{ $name }}">{{ $label }}</label>
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'form-control' . ($hasError ? ' is-invalid' : '')]) }}
    >{{ $value }}</textarea>
    @if($hasError)
        <div class="invalid-feedback">{{ $errorMessage }}</div>
    @endif
</div>

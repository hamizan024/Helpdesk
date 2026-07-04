{{--
    Bootstrap form group: label + text input + inline validation error.

    Props:
        label       – visible label text
        name        – input name attribute (also used for id and error lookup)
        type        – input type (default: text)
        placeholder – placeholder text
        value       – current field value (use old() or model value)
        bag         – named error bag (null = default bag)

    Additional attributes (required, autofocus, autocomplete, …) are forwarded
    directly to the <input> element via $attributes.
--}}
@props([
    'label',
    'name',
    'type'        => 'text',
    'placeholder' => '',
    'value'       => '',
    'bag'         => null,
])

@php
    $hasError     = $bag ? $errors->getBag($bag)->has($name) : $errors->has($name);
    $errorMessage = $bag ? $errors->getBag($bag)->first($name) : $errors->first($name);
@endphp

<div class="mb-3">
    <label class="form-label" for="{{ $name }}">{{ $label }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'form-control' . ($hasError ? ' is-invalid' : '')]) }}
    >
    @if($hasError)
        <div class="invalid-feedback">{{ $errorMessage }}</div>
    @endif
</div>

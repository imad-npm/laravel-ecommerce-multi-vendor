@props([
    'type' => 'text',
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'readonly' => false,
    'disabled' => false,
])

@php
$baseClasses = 'border-neutral-300 py-1 px-2.5 focus:border-primary focus:ring-primary rounded-md shadow-sm';
$fileInputClasses = 'block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-primary hover:file:bg-primary';

$finalClasses = $type === 'file' ? $fileInputClasses : $baseClasses;
@endphp

<input
    type="{{ $type }}"
    name="{{ $name }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }}
    {{ $readonly ? 'readonly' : '' }}
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => $finalClasses]) !!}
>
@props([
    'href' => '#',
    'disabled' => false,
    'variant' => 'default',
])

@php
    $variantClasses = [
        'default' => 'text-gray-600 hover:text-gray-900 underline',
        'primary' => 'text-primary hover:text-primary underline',
        'danger' => 'text-red-600 hover:text-red-900 underline',
        'success' => 'text-success-600 hover:text-success-900 underline',
        'secondary' => 'text-gray-500 hover:text-gray-700 underline',
    ];

    $classes = $variantClasses[$variant];

    if ($disabled) {
        $classes .= ' opacity-25 cursor-not-allowed';
    }
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} {{ $disabled ? 'aria-disabled=true' : '' }}>
    {{ $slot }}
</a>

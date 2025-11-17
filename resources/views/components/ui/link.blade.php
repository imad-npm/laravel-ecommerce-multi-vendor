@props([
    'href' => '#',
    'disabled' => false,
    'variant' => 'default',
])

@php
    $variantClasses = [
        'default' => 'text-gray-600 hover:text-gray-900 underline',
        'primary' => 'text-indigo-600 hover:text-indigo-900 underline',
        'danger' => 'text-red-600 hover:text-red-900 underline',
        'success' => 'text-green-600 hover:text-green-900 underline',
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

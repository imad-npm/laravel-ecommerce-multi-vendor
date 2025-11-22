@props([
    'href' => '#',
    'disabled' => false,
    'variant' => 'default',
])

@php
    $variantClasses = [
        'default' => 'text-neutral-600 hover:text-neutral-900 font-medium',
        'primary' => 'text-primary hover:text-primary font-medium',
        'danger' => 'text-red-600 hover:text-red-900 font-medium',
        'success' => 'text-success-600 hover:text-success-900 font-medium',
        'secondary' => 'text-neutral-500 hover:text-neutral-700 font-medium',
    ];

    $classes = $variantClasses[$variant];

    if ($disabled) {
        $classes .= ' opacity-25 cursor-not-allowed';
    }
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} {{ $disabled ? 'aria-disabled=true' : '' }}>
    {{ $slot }}
</a>

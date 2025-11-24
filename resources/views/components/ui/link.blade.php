@props([
    'href' => '#',
    'disabled' => false,
    'variant' => 'default',
])

@php
    $variantClasses = [
        'default' => 'text-primary hover:border-b hover:text-neutral-900 font-medium',
        'primary' => 'text-primary hover:text-primary hover:border-b font-medium',
        'danger' => 'text-red-600 hover:text-red-900 hover:border-b font-medium',
        'success' => 'text-success-600 hover:text-success-900 hover:border-b font-medium',
        'secondary' => 'text-neutral-500 hover:text-neutral-700 hover:border-b font-medium',
    ];

    $classes = $variantClasses[$variant];

    if ($disabled) {
        $classes .= ' opacity-25 cursor-not-allowed';
    }
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} {{ $disabled ? 'aria-disabled=true' : '' }}>
    {{ $slot }}
</a>

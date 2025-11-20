@props([
    'size' => 'md',
    'variant' => 'primary',
    'color' => 'gray', // New color prop
    'href' => null,
    'disabled' => false,
])

@php
    $baseClasses =
        ' inline-flex font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150';

    if ($variant == 'text') {
        $sizeClasses = [
            'sm' => ' text-xs',
            'md' => ' text-sm',
            'lg' => ' text-base',
        ];
    } else {
        $sizeClasses = ['sm' => 'px-3 py-1.5 text-xs', 'md' => 'px-4 py-2 text-sm', 'lg' => 'px-6 py-3 text-base'];
    }

    $variantColors = [
        'primary' => 'gray',
        'secondary' => 'gray',
        'danger' => 'red',
        'success' => 'green',
        'outline' => 'gray',
    ];

    $resolvedColor = $variantColors[$color] ?? 'gray';
    $variantClasses = [
        'primary' =>
            'bg-primary border border-transparent text-white hover:bg-neutral-700 focus:bg-neutral-700 active:bg-neutral-900 focus:ring-primary rounded-md',
        'secondary' =>
            'bg-white border border-neutral-300 text-neutral-700 hover:bg-neutral-50 focus:ring-primary rounded-md',
        'danger' =>
            'bg-red-600 border border-transparent text-white hover:bg-red-500 focus:bg-red-700 active:bg-red-900 focus:ring-red-500 rounded-md',
        'outline' =>
            'bg-transparent border border-neutral-300 text-neutral-700 hover:bg-neutral-100 focus:ring-primary rounded-md',
        'success' =>
            'bg-success-600 border border-transparent text-white hover:bg-success-700 focus:bg-success-700 active:bg-success-900 focus:ring-success-500 rounded-md',
        'text' => "text-{$resolvedColor}-600 hover:text-{$resolvedColor}-900  ", // New text variant
    ];

    if ($variant === 'text') {
        $classes = " {$sizeClasses[$size]} {$variantClasses[$variant]}";
    } else {
        $classes = "{$baseClasses} {$variantClasses[$variant]} {$sizeClasses[$size]}";
    }
    if ($disabled) {
        $classes .= ' opacity-25 cursor-not-allowed';
    }
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}
        {{ $disabled ? 'aria-disabled=true' : '' }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }} {{ $disabled ? 'disabled' : '' }}>
        {{ $slot }}
    </button>
@endif

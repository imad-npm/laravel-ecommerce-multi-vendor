@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-3 py-2 text-sm font-medium text-primary bg-neutral-100 rounded-md'
            : 'flex items-center px-3 py-2 text-sm font-medium text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900 rounded-md';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

@props([
    'name' => '',
    'value' => '',
    'rows' => 4,
])

<textarea
    name="{{ $name }}"
    rows="{{ $rows }}"
    {!! $attributes->merge(['class' => 'border-neutral-300 focus:border-primary focus:ring-primary rounded-md shadow-sm']) !!}
>{{ $value }}{{ $slot }}</textarea>

@props(['name', 'checked' => false])

<input type="checkbox" name="{{ $name }}" {{ $checked ? 'checked' : '' }} {!! $attributes->merge(['class' => 'rounded border-neutral-300 text-primary shadow-sm focus:ring-primary']) !!}>

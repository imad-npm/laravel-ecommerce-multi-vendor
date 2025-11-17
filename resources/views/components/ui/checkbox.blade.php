@props(['name', 'checked' => false])

<input type="checkbox" name="{{ $name }}" {{ $checked ? 'checked' : '' }} {!! $attributes->merge(['class' => 'rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500']) !!}>

@props(['name', 'value', 'checked' => false])

<input type="radio" name="{{ $name }}" value="{{ $value }}" {{ $checked ? 'checked' : '' }} {!! $attributes->merge(['class' => 'form-radio h-5 w-5 text-indigo-600']) !!}>

@props([
    'name',
    'options' => [],
    'selected' => '',
    'placeholder' => 'Select an option',
])

@php
    $selectedOption = collect($options)->firstWhere('value', $selected);
    $selectedLabel = $selectedOption['label'] ?? $placeholder;
@endphp

<div 
    x-data="{ 
        open: false, 
        selectedLabel: @js($selectedLabel), 
        selectedValue: @js($selected) 
    }" 
    {{ $attributes->merge(['class' => 'relative']) }}
>
    <input type="hidden" :name="'{{ $name }}'" x-model="selectedValue" />

    <button 
        type="button"
        @click="open = !open"
        @click.away="open = false"
        class="relative w-full cursor-pointer rounded-md bg-white py-1.5  pr-8 text-sm border border-neutral-300 shadow-sm focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition"
    ><span class="block truncate" x-text="selectedLabel"></span>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <svg class="h-4 w-4 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.19l3.71-3.96a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </span>
    </button>

    <div 
        x-show="open" 
        x-transition 
        class="absolute z-50 max-h-36 overflow-y-auto mt-1 w-full rounded-md bg-white py-1 text-sm shadow-lg ring-1 ring-black ring-opacity-5"
    >
        @foreach ($options as $option)
            <div 
                @click="selectedLabel = '{{ addslashes($option['label']) }}'; selectedValue = '{{ $option['value'] }}'; open = false"
                class="cursor-pointer select-none py-1.5 pl-3 pr-8 hover:bg-primary hover:text-white transition"
                :class="{ 'bg-primary text-white': selectedValue == '{{ $option['value'] }}' }"
            >
                <span class="block truncate" :class="{ 'font-semibold': selectedValue == '{{ $option['value'] }}' }">
                    {{ $option['label'] }}
                </span>
            </div>
        @endforeach
    </div>
</div>

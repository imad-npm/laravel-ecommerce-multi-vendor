@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl',
])

@php
$maxWidthClass = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<div
    x-data="{
        show: @js($show),
        focusables() {
            return [...$el.querySelectorAll('a, button, input:not([type=hidden]), textarea, select, [tabindex]:not([tabindex=-1])')]
                .filter(el => !el.disabled);
        },
        trapFocus(e) {
            if (!this.show) return;
            const focusable = this.focusables();
            if (!focusable.length) return;

            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (e.key === 'Tab') {
                if (e.shiftKey && document.activeElement === first) {
                    e.preventDefault(); last.focus();
                } else if (!e.shiftKey && document.activeElement === last) {
                    e.preventDefault(); first.focus();
                }
            }
        }
    }"
    x-init="$watch('show', value => document.body.classList.toggle('overflow-y-hidden', value))"
    x-on:keydown.window.escape="show = false"
    x-on:keydown.tab.prevent.stop="trapFocus($event)"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') show = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') show = false"
    x-show="show"
    class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0"
    aria-labelledby="{{ $name }}-title"
    role="dialog"
    aria-modal="true"
    style="display: {{ $show ? 'flex' : 'none' }};"
>
    {{-- Overlay --}}
    <div
        x-show="show"
        x-transition.opacity
        class="fixed inset-0 bg-black/40"
        x-on:click="show = false"
    ></div>

    {{-- Modal Content --}}
    <div
        x-show="show"
        x-transition
        x-trap.noscroll="show"
        class="relative w-full {{ $maxWidthClass }}
         bg-white rounded-xl shadow-2xl overflow-hidden 
         transform transition-all"
    >
        {{ $slot }}
    </div>
</div>

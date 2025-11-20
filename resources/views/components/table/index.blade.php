<div class="overflow-x-auto rounded-lg border border-neutral-200 dark:border-neutral-700">
    <table {{ $attributes->merge(['class' => 'w-full text-sm text-left text-neutral-500 dark:text-neutral-400']) }}>
        {{ $slot }}
    </table>
</div>
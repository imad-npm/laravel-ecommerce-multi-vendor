<div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
    <table {{ $attributes->merge(['class' => 'w-full text-sm text-left text-gray-500 dark:text-gray-400']) }}>
        {{ $slot }}
    </table>
</div>
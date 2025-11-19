<tr>
    <td {{ $attributes->merge(['class' => 'px-6 py-4 text-center text-gray-500']) }} colspan="100%">
        {{ $slot->isEmpty() ? 'No data available.' : $slot }}
    </td>
</tr>
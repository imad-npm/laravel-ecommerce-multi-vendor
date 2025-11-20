@props([
    'columns' => [], // ['key' => 'Label']
    'rows' => [],    // Collection or array
    'empty' => 'No data found.',
])

<table {{ $attributes->class('min-w-full table-auto text-sm') }}>
    <thead class="bg-neutral-100 text-neutral-600 font-semibold text-xs uppercase">
        <tr>
            @foreach($columns as $key => $label)
                <th class="px-3 py-2 text-left">{{ $label }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody class="divide-y divide-neutral-200">
        @forelse($rows as $row)
            <tr class="hover:bg-neutral-50">
                @foreach($columns as $key => $label)
                    <td class="px-3 py-2">
                        {{-- Render the slot for this column if defined --}}
                        @isset(${"slot_$key"})
                            {{-- Pass current row as $row to the slot --}}
                            {{ ${"slot_$key"}(['row' => $row]) }}
                        @else
                            {{ data_get($row, $key) }}
                        @endisset
                    </td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns) }}" class="px-3 py-4 text-center text-neutral-500 italic">
                    {{ $empty }}
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

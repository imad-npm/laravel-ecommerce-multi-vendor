<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Vendor Earning') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.vendor-earnings.update', $vendorEarning) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="payout_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Payout') }}</label>
                            <select name="payout_id" id="payout_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">{{ __('Select Payout') }}</option>
                                @foreach ($payouts as $payout)
                                    <option value="{{ $payout->id }}" @selected($vendorEarning->payout_id == $payout->id)>{{ $payout->id }} - {{ $payout->vendor->name }} - {{ $payout->amount }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="is_paid" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Is Paid') }}</label>
                            <select name="is_paid" id="is_paid" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="1" @selected($vendorEarning->is_paid)>{{ __('Yes') }}</option>
                                <option value="0" @selected(!$vendorEarning->is_paid)>{{ __('No') }}</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-primary hover:bg-primary text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

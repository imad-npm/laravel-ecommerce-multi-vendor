@php
    use App\Enums\PayoutStatus;
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Edit Payout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.payouts.update', $payout) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="vendor_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Vendor') }}</label>
                            <select name="vendor_id" id="vendor_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" @selected($payout->vendor_id == $vendor->id)>{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Amount') }}</label>
                            <input type="text" name="amount" id="amount" value="{{ $payout->amount }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="transaction_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Transaction ID') }}</label>
                            <input type="text" name="transaction_id" id="transaction_id" value="{{ $payout->transaction_id }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Status') }}</label>
                            <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @foreach(PayoutStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected($payout->status->value == $status->value)>{{ ucfirst($status->value) }}</option>
                                @endforeach
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

<x-app-layout>
    <x-slot name="header">
        <h2 class="flex justify-between font-semibold text-xl text-gray-800 leading-tight">
        <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm"
            href="{{ route('dashboard') }}">
            {{ __('messages.return') }}
        </a>
        <a class="text-center font-bold text-2xl text-gray-900 leading-tight">
        {{ __('messages.return_requests') }}
        </a>
        <a>
        </a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8 grid grid-cols-2 gap-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h4 class="text-md font-semibold mb-2">{{ __('messages.purchased_products') }}</h4>
                <form method="GET" action="{{ route('returns.index') }}" class="mb-6 flex justify-between items-center">
                    <div class="flex space-x-4">
                        <div>
                            <label for="buy_status" class="font-semibold text-gray-700">Status</label>
                            <select name="buy_status" id="buy_status" class="border p-2">
                                <option value="">{{ __('messages.all') }}</option>
                                <option value="approved" {{ request('buy_status') == 'approved' ? 'selected' : '' }}>{{ __('messages.approved') }}</option>
                                <option value="pending" {{ request('buy_status') == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                                <option value="rejected" {{ request('buy_status') == 'rejected' ? 'selected' : '' }}>{{ __('messages.rejected') }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="buy_sort" class="font-semibold text-gray-700">{{ __('messages.sort_by') }}</label>
                            <select name="buy_sort" id="buy_sort" class="border p-2">
                                <option value="date_asc" {{ request('buy_sort') == 'date_asc' ? 'selected' : '' }}>{{__('messages.date_asc')}}</option>
                                <option value="date_desc" {{ request('buy_sort') == 'date_desc' ? 'selected' : '' }}>{{__('messages.date_desc')}}</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ __('messages.filter') }}</button>
                </form>
                @if ($returns->where('advertisement.type', 'buy')->isNotEmpty())
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border">{{ __('messages.id') }}</th>
                                <th class="px-4 py-2 border">{{ __('messages.user') }}</th>
                                <th class="px-4 py-2 border">{{ __('messages.reason') }}</th>
                                <th class="px-4 py-2 border">{{ __('messages.image') }}</th>
                                <th class="px-4 py-2 border">{{ __('messages.status') }}</th>
                                <th class="px-4 py-2 border">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($returns->where('advertisement.type', 'buy') as $return)
                                <tr>
                                    <td class="border px-4 py-2">{{ $return->id }}</td>
                                    <td class="border px-4 py-2">{{ $return->user->name }}</td>
                                    <td class="border px-4 py-2">{{ $return->reason }}</td>
                                    <td class="border px-4 py-2">
                                        @if($return->image)
                                            <a href="{{ asset('storage/' . $return->image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $return->image) }}" alt="{{ __('messages.return_image') }}" class="w-16 h-16 object-cover cursor-pointer">
                                            </a>
                                        @else
                                            {{ __('messages.no_image') }}
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ ucfirst($return->status) }}</td>
                                    <td class="border px-4 py-2">
                                        @if($return->status == 'pending')
                                            <form action="{{ route('returns.approve', $return->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">{{ __('messages.approve') }}</button>
                                            </form>
                                            <form action="{{ route('returns.reject', $return->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">{{ __('messages.reject') }}</button>
                                            </form>
                                        @else
                                            <span class="text-gray-600">{{ __('messages.no_actions_available') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>{{ __('messages.no_purchased_returns') }}</p>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h4 class="text-md font-semibold mb-2">{{ __('messages.rented_products') }}</h4>
                <form method="GET" action="{{ route('returns.index') }}" class="mb-6 flex justify-between items-center">
                <div class="flex space-x-4">
                    <div>
                        <label for="rent_status" class="font-semibold text-gray-700">Status</label>
                        <select name="rent_status" id="rent_status" class="border p-2">
                            <option value="">{{ __('messages.all') }}</option>
                            <option value="approved" {{ request('rent_status') == 'approved' ? 'selected' : '' }}>{{ __('messages.approved') }}</option>
                            <option value="pending" {{ request('rent_status') == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                            <option value="rejected" {{ request('rent_status') == 'rejected' ? 'selected' : '' }}>{{ __('messages.rejected') }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="rent_sort" class="font-semibold text-gray-700">{{ __('messages.sort_by') }}</label>
                        <select name="rent_sort" id="rent_sort" class="border p-2">
                            <option value="date_asc" {{ request('rent_sort') == 'date_asc' ? 'selected' : '' }}>{{__('messages.date_asc')}}</option>
                            <option value="date_desc" {{ request('rent_sort') == 'date_desc' ? 'selected' : '' }}>{{__('messages.date_desc')}}</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ __('messages.filter') }}</button>
            </form>
                @if ($returns->where('advertisement.type', 'rent')->isNotEmpty())
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border">{{ __('messages.id') }}</th>
                                <th class="px-4 py-2 border">{{ __('messages.user') }}</th>
                                <th class="px-4 py-2 border">{{ __('messages.reason') }}</th>
                                <th class="px-4 py-2 border">{{ __('messages.image') }}</th>
                                <th class="px-4 py-2 border">{{ __('messages.status') }}</th>
                                <th class="px-4 py-2 border">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($returns->where('advertisement.type', 'rent') as $return)
                                <tr>
                                    <td class="border px-4 py-2">{{ $return->id }}</td>
                                    <td class="border px-4 py-2">{{ $return->user->name }}</td>
                                    <td class="border px-4 py-2">{{ $return->reason }}</td>
                                    <td class="border px-4 py-2">
                                        @if($return->image)
                                            <a href="{{ asset('storage/' . $return->image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $return->image) }}" alt="{{ __('messages.return_image') }}" class="w-16 h-16 object-cover cursor-pointer">
                                            </a>
                                        @else
                                            {{ __('messages.no_image') }}
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ ucfirst($return->status) }}</td>
                                    <td class="border px-4 py-2">
                                        @if($return->status == 'pending')
                                            <form action="{{ route('returns.approve', $return->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">{{ __('messages.approve') }}</button>
                                            </form>
                                            <form action="{{ route('returns.reject', $return->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">{{ __('messages.reject') }}</button>
                                            </form>
                                        @else
                                            <span class="text-gray-600">{{ __('messages.no_actions_available') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>{{ __('messages.no_rented_returns') }}</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

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

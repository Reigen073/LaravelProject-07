<x-app-layout>
    <x-slot name="header">
    <h2 class="flex justify-between font-semibold text-xl text-gray-800 leading-tight space-x-2">
        <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm"
            href="{{ route('dashboard') }}">
            {{ __('messages.return') }}
        </a>
                <a class="text-center font-bold text-2xl text-gray-900 leading-tight">
            {{ __('messages.my_advert_agenda') }}
        </a>
        <a>
        </a>
    </x-slot>
    @if (session('success'))
        <div class="flex justify-center">
            <div class="bg-green-500 text-white p-3 rounded-lg shadow-md text-center w-1/2">
                {{ session('success') }}
            </div>
        </div>
    @endif
    <div class="container mx-auto p-6">
        @if(isset($advertisements))
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold mb-4">{{ __('messages.my_rented_products') }}</h3>
            <form method="GET" id="filter-1" action="{{ route('advertisements.agenda') }}" class="mb-6 flex justify-between items-center">
            <input type="hidden" name="filter_set" value="myrented">    
            <div class="flex space-x-4">
                    <div>
                        <label for="category" class="font-semibold text-gray-700">{{ __('messages.category') }}</label>
                        <select name="category" id="category" class="border p-2">
                            <option value="">{{ __('messages.all') }}</option>
                            <option value="games" {{ request('category') == 'games' ? 'selected' : '' }}>{{ __('messages.games') }}</option>
                            <option value="household" {{ request('category') == 'household' ? 'selected' : '' }}>{{ __('messages.household') }}</option>
                            <option value="outdoor" {{ request('category') == 'outdoor' ? 'selected' : '' }}>{{ __('messages.outdoor') }}</option>
                        </select>
                    </div>

                    <div>
                        <label for="condition" class="font-semibold text-gray-700">{{ __('messages.condition') }}</label>
                        <select name="condition" id="condition" class="border p-2">
                            <option value="">{{ __('messages.all') }}</option>
                            <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>{{ __('messages.new') }}</option>
                            <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>{{ __('messages.used') }}</option>
                            <option value="refurbished" {{ request('condition') == 'refurbished' ? 'selected' : '' }}>{{ __('messages.refurbished') }}</option>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="font-semibold text-gray-700">Status</label>
                        <select name="status" id="status" class="border p-2">
                            <option value="">{{ __('messages.all') }}</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>{{ __('messages.available') }}</option>
                            <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>{{ __('messages.rented') }}</option>
                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>{{ __('messages.sold') }}</option>
                        </select>
                    </div>

                    <div>
                        <label for="sort" class="font-semibold text-gray-700">{{ __('messages.sort_by') }}</label>
                        <select name="sort" id="sort" class="border p-2">
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{__('messages.price_asc')}}</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{__('messages.price_desc')}}</option>
                            <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>{{__('messages.title_asc')}}</option>
                            <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>{{__('messages.title_desc')}}</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ __('messages.filter') }}</button>
            </form>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.title') }}</th>
                            <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.pickup_date') }}</th>
                            <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.return_date') }}</th>
                            <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.return') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($advertisements as $advertisement)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-800">{{ $advertisement->title }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($advertisement->created_at)->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($advertisement->expires_at)->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 text-gray-600">
                                    <form action="{{ route('returns.store', $advertisement->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mt-2">
                                            <label for="image" class="block text-gray-700">{{ __('messages.upload_photo') }}</label>
                                            <input type="hidden" name="reason" id="reason" class="w-full border p-2 rounded" value="rented" required>
                                            <input type="file" name="image" id="image" class="border p-2 rounded" required>
                                        </div>
                                        <button type="submit" class="mt-3 bg-green-500 text-white px-4 py-2 rounded">{{ __('messages.return') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="container mx-auto p-6">
        <div class="flex flex-wrap -mx-4">
            <div class="w-full md:w-1/2 px-4">
                <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                    <h3 class="text-xl font-semibold mb-4">{{ __('messages.expiring_ads') }}</h3>
                    <form method="GET" id="filter-2" action="{{ route('advertisements.agenda') }}" class="mb-6 flex justify-between items-center">
                    <input type="hidden" name="filter_set" value="expiring">
                        <div class="flex space-x-4">
                            <div>
                                <label for="category" class="font-semibold text-gray-700">{{ __('messages.category') }}</label>
                                <select name="category" id="category" class="border p-2">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="games" {{ request('category') == 'games' ? 'selected' : '' }}>{{ __('messages.games') }}</option>
                                    <option value="household" {{ request('category') == 'household' ? 'selected' : '' }}>{{ __('messages.household') }}</option>
                                    <option value="outdoor" {{ request('category') == 'outdoor' ? 'selected' : '' }}>{{ __('messages.outdoor') }}</option>
                                </select>
                            </div>

                            <div>
                                <label for="condition" class="font-semibold text-gray-700">{{ __('messages.condition') }}</label>
                                <select name="condition" id="condition" class="border p-2">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>{{ __('messages.new') }}</option>
                                    <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>{{ __('messages.used') }}</option>
                                    <option value="refurbished" {{ request('condition') == 'refurbished' ? 'selected' : '' }}>{{ __('messages.refurbished') }}</option>
                                </select>
                            </div>

                            <div>
                                <label for="status" class="font-semibold text-gray-700">Status</label>
                                <select name="status" id="status" class="border p-2">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>{{ __('messages.available') }}</option>
                                    <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>{{ __('messages.rented') }}</option>
                                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>{{ __('messages.sold') }}</option>
                                </select>
                            </div>

                            <div>
                                <label for="sort" class="font-semibold text-gray-700">{{ __('messages.sort_by') }}</label>
                                <select name="sort" id="sort" class="border p-2">
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{__('messages.price_asc')}}</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{__('messages.price_desc')}}</option>
                                    <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>{{__('messages.title_asc')}}</option>
                                    <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>{{__('messages.title_desc')}}</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ __('messages.filter') }}</button>
                    </form>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.title') }}</th>
                                    <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.expires_on') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expiringAdvertisements as $advertisement)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 text-gray-800">{{ $advertisement->title }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($advertisement->expires_at)->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/2 px-4">
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-4">{{ __('messages.rented_out_products') }}</h3>
                    <form method="GET" id="filter-3" action="{{ route('advertisements.agenda') }}" class="mb-6 flex justify-between items-center">
                        <input type="hidden" name="filter_set" value="rentedout">
                        <div class="flex space-x-4">
                            <div>
                                <label for="category" class="font-semibold text-gray-700">{{ __('messages.category') }}</label>
                                <select name="category" id="category" class="border p-2">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="games" {{ request('category') == 'games' ? 'selected' : '' }}>{{ __('messages.games') }}</option>
                                    <option value="household" {{ request('category') == 'household' ? 'selected' : '' }}>{{ __('messages.household') }}</option>
                                    <option value="outdoor" {{ request('category') == 'outdoor' ? 'selected' : '' }}>{{ __('messages.outdoor') }}</option>
                                </select>
                            </div>

                            <div>
                                <label for="condition" class="font-semibold text-gray-700">{{ __('messages.condition') }}</label>
                                <select name="condition" id="condition" class="border p-2">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>{{ __('messages.new') }}</option>
                                    <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>{{ __('messages.used') }}</option>
                                    <option value="refurbished" {{ request('condition') == 'refurbished' ? 'selected' : '' }}>{{ __('messages.refurbished') }}</option>
                                </select>
                            </div>

                            <div>
                                <label for="status" class="font-semibold text-gray-700">Status</label>
                                <select name="status" id="status" class="border p-2">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>{{ __('messages.available') }}</option>
                                    <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>{{ __('messages.rented') }}</option>
                                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>{{ __('messages.sold') }}</option>
                                </select>
                            </div>

                            <div>
                                <label for="sort" class="font-semibold text-gray-700">{{ __('messages.sort_by') }}</label>
                                <select name="sort" id="sort" class="border p-2">
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{__('messages.price_asc')}}</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{__('messages.price_desc')}}</option>
                                    <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>{{__('messages.title_asc')}}</option>
                                    <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>{{__('messages.title_desc')}}</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ __('messages.filter') }}</button>
                    </form>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.title') }}</th>
                                    <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.return_on') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rentedAdvertisements as $advertisement)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 text-gray-800">{{ $advertisement->title }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($advertisement->expires_at)->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-1/2 px-4">
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-4">{{ __('messages.biddings') }}</h3>
                    <form method="GET" id="filter-4" action="{{ route('advertisements.agenda') }}" class="mb-6 flex justify-between items-center">
                    <input type="hidden" name="filter_set" value="bidding">    
                        <div class="flex space-x-4">
                            <div>
                                <label for="sort" class="font-semibold text-gray-700">{{ __('messages.sort_by') }}</label>
                                <select name="sort" id="sort" class="border p-2">
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{__('messages.price_asc')}}</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{__('messages.price_desc')}}</option>
                                    <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>{{__('messages.title_asc')}}</option>
                                    <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>{{__('messages.title_desc')}}</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ __('messages.filter') }}</button>
                    </form>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.advertisement') }}</th>
                                <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.user') }}</th>
                                <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.bid') }}</th>
                                <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($biddings as $bidding)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-gray-800">{{ $bidding->advertisement->title }}</td>
                                    <td class="px-6 py-4 text-gray-800">{{ $bidding->user->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">€{{ number_format($bidding->bid_amount, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        @if($bidding->status === 'accepted')
                                            <span class="text-green-500 font-bold">{{ __('messages.accepted') }}</span>
                                        @elseif($bidding->status === 'rejected')
                                            <span class="text-red-500 font-bold">{{ __('messages.rejected') }}</span>
                                        @else
                                            <form action="{{ route('advertisements.biddingAccept', $bidding->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">{{ __('messages.accept') }}</button>
                                            </form>
                                            <form action="{{ route('advertisements.biddingReject', $bidding->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">{{ __('messages.reject') }}</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>

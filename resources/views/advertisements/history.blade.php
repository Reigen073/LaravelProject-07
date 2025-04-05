<x-app-layout>
    <x-slot name="header">
        <h2 class="flex justify-between font-bold text-2xl text-gray-900 leading-tight">
        <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm"
            href="{{ route('dashboard') }}">
            {{ __('messages.return') }}
        </a>
        <a class="text-center font-bold text-2xl text-gray-900 leading-tight">
        {{ __('messages.purchased_products') }}
        </a>
        <a>
        </a>
        </h2>
    </x-slot>
    @if (session('success'))
        <div class="flex justify-center">
            <div class="bg-green-500 text-white p-3 rounded-lg shadow-md text-center w-1/2">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="flex justify-center">
            <div class="bg-red-500 text-white p-3 rounded-lg shadow-md text-center w-1/2">
                {{ session('error') }}
            </div>
        </div>
    @endif
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold mb-4">{{ __('messages.your_purchased_products') }}</h3>
            <form method="GET" action="{{ route('advertisements.history') }}" class="mb-6 flex justify-between items-center">
                <div class="flex space-x-4">
                    <div>
                        <label for="category" class="font-semibold text-gray-700">{{ __('messages.category') }}</label>
                        <select name="category" id="category" class="border p-2">
                            <option value="">{{ __('messages.all') }}</option>
                            <option value="games" {{ request('category') == 'games' ? 'selected' : '' }}>Games</option>
                            <option value="household" {{ request('category') == 'household' ? 'selected' : '' }}>{{ __('messages.household') }}</option>
                            <option value="outdoor" {{ request('category') == 'outdoor' ? 'selected' : '' }}>Buiten</option>
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
                            <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>{{__('messages.title_asc')}}/option>
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
                            <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.price') }}</th>
                            <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.purchased_on') }}</th>
                            <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">{{ __('messages.return') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($advertisements as $advertisment)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-800">{{ $advertisment->title }}</td>
                                <td class="px-6 py-4 text-gray-600">€{{ number_format($advertisment->price, 2) }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($advertisment->created_at)->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 text-gray-600">
                                    <form action="{{ route('returns.store', $advertisment->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div>
                                            <label for="reason" class="block text-gray-700">{{ __('messages.reason_for_return') }}</label>
                                            <textarea name="reason" id="reason" class="w-full border p-2 rounded" required></textarea>
                                        </div>
                                        <div class="mt-2">
                                            <label for="image" class="block text-gray-700">{{ __('messages.upload_photo') }}</label>
                                            <input type="file" name="image" id="image" class="border p-2 rounded">
                                        </div>
                                        <button type="submit" class="mt-3 bg-red-500 text-white px-4 py-2 rounded">{{ __('messages.request_return') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

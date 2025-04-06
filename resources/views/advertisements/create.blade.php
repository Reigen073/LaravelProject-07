<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           {{ __('messages.new_advertisement') }}
        </h2>
        
    </x-slot>
     @if (session('error'))
        <div class="flex justify-center">
            <div class="bg-red-500 text-white p-3 rounded-lg shadow-md text-center w-1/2">
                {{ session('error') }}
            </div>
        </div>
        
        @endif
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block" 
            href="{{ route('advertisements.upload.form') }}">
            {{ __('messages.upload_csv') }}
            </a>
            <form method="POST" action="/advertisements" enctype="multipart/form-data">
                @csrf
                <div>
                    <label for="title">{{ __('messages.title') }}</label>
                    <input type="text" name="title" id="title" class="block w-full border p-2" required>
                </div>

                <div class="mt-4">
                    <label for="description">{{ __('messages.description') }}</label>
                    <textarea name="description" id="description" class="block w-full border p-2" required></textarea>
                </div>

                <div class="mt-4">
                    <label for="price">{{ __('messages.price') }} (€)</label>
                    <input type="number" name="price" id="price" step="0.01" class="block w-full border p-2" required>
                </div>

                <div class="mt-4">
                    <label for="category">{{ __('messages.category') }}</label>
                    <select name="category" id="category" class="block w-full border p-2" required>
                        <option value="games">{{ __('messages.games') }}</option>
                        <option value="household">{{ __('messages.household') }}</option>
                        <option value="outdoor">{{ __('messages.outdoor') }}</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="type">{{ __('messages.type') }}</label>
                    <select name="type" id="type" class="block w-full border p-2" required>
                        <option value="buy">{{__('messages.buy')}}</option>
                        <option value="rent">{{ __('messages.rent') }}</option>
                        <option value='bidding'>{{__('messages.create_bid')}}</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="status">{{__('messages.status')}}</label>
                    <select name="status" id="status" class="block w-full border p-2" required>
                        <option value="available">{{__('messages.available')}}</option>
                        <option value="rented">{{ __('messages.rented') }}</option>
                        <option value="sold">{{ __('messages.sold')}}</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="condition">{{ __('messages.condition') }}</label>
                    <select name="condition" id="condition" class="block w-full border p-2" required>
                        <option value="new">{{ __('messages.new') }}</option>
                        <option value="used">{{__('messages.used')}}</option>
                        <option value="refurbished">{{__('messages.refurbished')}}</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="image">{{ __('messages.image') }}</label>
                    <input type="file" name="image" id="image" class="block w-full border p-2" accept="image/*">
                </div>

                <div class="mt-4">
                    <label for="expires_at">{{ __('messages.expires_on') }} </label>
                    <input type="date" name="expires_at" id="expires_at" class="block w-full border p-2" required>
                </div>
                <div>
                    {{ __('messages.settings_wear') }}
                </div>
                <div class="mt-4">
                    <label for="wear_rate">{{ __('messages.wear_rate') }} (0-1)</label>
                    <input type="number" name="wear_rate" id="wear_rate" class="block w-full border p-2" step="0.01" min="0" max="1" required>
                </div>
                <div class="mt-6">
                    <label for="related_advertisements" class="block text-lg font-semibold mb-2">{{ __('messages.related_adverts') }}</label>
                    <div class="border p-4 rounded-lg shadow-md bg-gray-50 overflow-y-auto" style="max-height: calc(3 * 40px);">
                        @foreach ($advertisements as $advertisement)
                            <div class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded">
                                <input type="checkbox" name="related_advertisements[]" value="{{ $advertisement->id }}" id="ad_{{ $advertisement->id }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="ad_{{ $advertisement->id }}" class="text-gray-800 cursor-pointer">{{ $advertisement->title }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ __('messages.place_advertisement') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight text-center">
            {{ __('messages.edit_advertisement') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden p-6">
                <form method="POST" action="{{ route('advertisements.update', $advertisement->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">{{ __('messages.title') }}</label>
                        <input type="text" name="title" value="{{ $advertisement->title }}" class="w-full border rounded-lg p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">{{ __('messages.description') }}</label>
                        <textarea name="description" class="w-full border rounded-lg p-2" required>{{ $advertisement->description }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">{{ __('messages.price') }} (€)</label>
                        <input type="number" name="price" value="{{ $advertisement->price }}" step="0.01" class="w-full border rounded-lg p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">{{ __('messages.category') }}</label>
                        <select name="category" class="w-full border rounded-lg p-2" required>
                            <option value="games" {{ $advertisement->category == 'games' ? 'selected' : '' }}>{{ __('messages.games') }}</option>
                            <option value="household" {{ $advertisement->category == 'household' ? 'selected' : '' }}>{{ __('messages.household') }}</option>
                            <option value="outdoor" {{ $advertisement->category == 'outdoor' ? 'selected' : '' }}>{{ __('messages.outdoor') }}</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">{{ __('messages.type') }}</label>
                        <select name="type" class="w-full border rounded-lg p-2" required>
                            <option value="buy" {{ $advertisement->type == 'buy' ? 'selected' : '' }}>{{ __('messages.buy') }}</option>
                            <option value="rent" {{ $advertisement->type == 'rent' ? 'selected' : '' }}>{{ __('messages.rent') }}</option>
                            <option value="bidding" {{ $advertisement->type == 'bidding' ? 'selected' : '' }}>{{ __('messages.bidding') }}</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">{{ __('messages.status') }}</label>
                        <select name="status" class="w-full border rounded-lg p-2" required>
                            <option value="available" {{ $advertisement->status == 'available' ? 'selected' : '' }}>{{ __('messages.available') }}</option>
                            <option value="rented" {{ $advertisement->status == 'rented' ? 'selected' : '' }}>{{ __('messages.rented') }}</option>
                            <option value="sold" {{ $advertisement->status == 'sold' ? 'selected' : '' }}>{{ __('messages.sold') }}</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">{{ __('messages.condition') }}</label>
                        <select name="condition" class="w-full border rounded-lg p-2" required>
                            <option value="new" {{ $advertisement->condition == 'new' ? 'selected' : '' }}>{{ __('messages.new') }}</option>
                            <option value="used" {{ $advertisement->condition == 'used' ? 'selected' : '' }}>{{ __('messages.used') }}</option>
                            <option value="refurbished" {{ $advertisement->condition == 'refurbished' ? 'selected' : '' }}>{{ __('messages.refurbished') }}</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">{{ __('messages.image') }}</label>
                        <input type="file" name="image" class="w-full border rounded-lg p-2" accept="image/*">
                        @if($advertisement->image)
                            <p class="text-gray-600 mt-2">{{ __('messages.current_image') }}</p>
                            <img src="{{ asset('storage/' . $advertisement->image) }}" alt="Advertisement image" class="w-32 h-32 mt-2 rounded-lg">
                        @endif
                    </div>

                    <div class="mt-4">
                        <label for="expires_at">{{ __('messages.expires_on') }}</label>
                        <input type="date" name="expires_at" value="{{ $advertisement->expires_at }}" class="block w-full border p-2" required>
                    </div>

                    <h3 class="text-lg font-semibold mt-6 mb-2">{{ __('messages.wear_settings') }}</h3>

                    <div class="mt-4">
                        <label for="wear_rate">{{ __('messages.wear_rate') }} (0-1)</label>
                        <input type="number" name="wear_rate" id="wear_rate" value="{{ $advertisement->wear_rate }}" class="block w-full border p-2" step="0.01" min="0" max="1" required>
                    </div>

                    <div class="mt-6">
                        <label for="related_advertisements" class="block text-lg font-semibold mb-2">{{ __('messages.related_ads') }}</label>
                        <div class="border p-4 rounded-lg shadow-md bg-gray-50 overflow-y-auto" style="max-height: calc(3 * 40px);">
                            @foreach ($advertisements as $relatedAd)
                                <div class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded">
                                    <input type="checkbox" name="related_advertisements[]" value="{{ $relatedAd->id }}" id="ad_{{ $relatedAd->id }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    <label for="ad_{{ $relatedAd->id }}" class="text-gray-800 cursor-pointer">{{ $relatedAd->title }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-between mt-4">
                        <a href="{{ url()->previous() }}"
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('messages.cancel') }}
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('messages.save') }}
                        </button>
                    </div>
                </form>

                <form method="POST" action="{{ route('advertisements.destroy', $advertisement->id) }}" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('messages.delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

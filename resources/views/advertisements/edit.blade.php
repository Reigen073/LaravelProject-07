<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight text-center">
            Advertentie Bewerken
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden p-6">
                <form method="POST" action="{{ route('advertisements.update', $advertisement->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Titel</label>
                        <input type="text" name="title" value="{{ $advertisement->title }}" class="w-full border rounded-lg p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Beschrijving</label>
                        <textarea name="description" class="w-full border rounded-lg p-2" required>{{ $advertisement->description }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Prijs (€)</label>
                        <input type="number" name="price" value="{{ $advertisement->price }}" step="0.01" class="w-full border rounded-lg p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Categorie</label>
                        <select name="category" class="w-full border rounded-lg p-2" required>
                            <option value="games" {{ $advertisement->category == 'games' ? 'selected' : '' }}>Games</option>
                            <option value="household" {{ $advertisement->category == 'household' ? 'selected' : '' }}>Huishoud</option>
                            <option value="outdoor" {{ $advertisement->category == 'outdoor' ? 'selected' : '' }}>Buiten</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Type</label>
                        <select name="type" class="w-full border rounded-lg p-2" required>
                            <option value="buy" {{ $advertisement->type == 'buy' ? 'selected' : '' }}>Koop</option>
                            <option value="rent" {{ $advertisement->type == 'rent' ? 'selected' : '' }}>Verhuur</option>
                            <option value="bidding" {{ $advertisement->type == 'bidding' ? 'selected' : '' }}>Bieden</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Status</label>
                        <select name="status" class="w-full border rounded-lg p-2" required>
                            <option value="available" {{ $advertisement->status == 'available' ? 'selected' : '' }}>Beschikbaar</option>
                            <option value="rented" {{ $advertisement->status == 'rented' ? 'selected' : '' }}>Verhuurd</option>
                            <option value="sold" {{ $advertisement->status == 'sold' ? 'selected' : '' }}>Verkocht</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Conditie</label>
                        <select name="condition" class="w-full border rounded-lg p-2" required>
                            <option value="new" {{ $advertisement->condition == 'new' ? 'selected' : '' }}>Nieuw</option>
                            <option value="used" {{ $advertisement->condition == 'used' ? 'selected' : '' }}>Gebruikt</option>
                            <option value="refurbished" {{ $advertisement->condition == 'refurbished' ? 'selected' : '' }}>Gereviseerd</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Afbeelding</label>
                        <input type="file" name="image" class="w-full border rounded-lg p-2" accept="image/*">
                        @if($advertisement->image)
                            <p class="text-gray-600 mt-2">Huidige afbeelding:</p>
                            <img src="{{ asset('storage/' . $advertisement->image) }}" alt="Advertentie afbeelding" class="w-32 h-32 mt-2 rounded-lg">
                        @endif
                    </div>

                    <div class="mt-4">
                        <label for="expires_at">Verloopt op</label>
                        <input type="date" name="expires_at" value="{{ $advertisement->expires_at }}" class="block w-full border p-2" required>
                    </div>

                    <div>
                        Instellingen voor slijtage
                    </div>
                    <div class="mt-4">
                        <label for="wear_rate">Slijtagesnelheid (0-1)</label>
                        <input type="number" name="wear_rate" id="wear_rate" value="{{ $advertisement->wear_rate }}" class="block w-full border p-2" step="0.01" min="0" max="1" required>
                    </div>

                    <div class="mt-6">
                    <label for="related_advertisements" class="block text-lg font-semibold mb-2">Gerelateerde advertenties</label>
                        <div class="border p-4 rounded-lg shadow-md bg-gray-50 overflow-y-auto" style="max-height: calc(3 * 40px);">
                            @foreach ($advertisements as $advertisement)
                                <div class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded">
                                    <input type="checkbox" name="related_advertisements[]" value="{{ $advertisement->id }}" id="ad_{{ $advertisement->id }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    <label for="ad_{{ $advertisement->id }}" class="text-gray-800 cursor-pointer">{{ $advertisement->title }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-between mt-4">
                        <a href="{{ url()->previous() }}"
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Annuleren
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Opslaan
                        </button>
                    </div>
                </form>
                <form method="POST" action="{{ route('advertisements.destroy', $advertisement->id) }}" 
                    class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Verwijderen
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

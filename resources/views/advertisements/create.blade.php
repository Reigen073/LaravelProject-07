<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nieuwe Advertentie
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
            Upload via CSV
            </a>
            <form method="POST" action="/advertisements" enctype="multipart/form-data"> <!-- enctype voor bestand uploaden -->
                @csrf
                <div>
                    <label for="title">Titel</label>
                    <input type="text" name="title" id="title" class="block w-full border p-2" required>
                </div>

                <div class="mt-4">
                    <label for="description">Beschrijving</label>
                    <textarea name="description" id="description" class="block w-full border p-2" required></textarea>
                </div>

                <div class="mt-4">
                    <label for="price">Prijs (€)</label>
                    <input type="number" name="price" id="price" step="0.01" class="block w-full border p-2" required>
                </div>

                <div class="mt-4">
                    <label for="category">Categorie</label>
                    <select name="category" id="category" class="block w-full border p-2" required>
                        <option value="games">Games</option>
                        <option value="household">Huishoud</option>
                        <option value="outdoor">Buiten</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="block w-full border p-2" required>
                        <option value="buy">Koop</option>
                        <option value="rent">Verhuur</option>
                        <option value='bidding'>Bieden</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="block w-full border p-2" required>
                        <option value="available">Beschikbaar</option>
                        <option value="rented">Verhuurd</option>
                        <option value="sold">Verkocht</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="condition">Conditie</label>
                    <select name="condition" id="condition" class="block w-full border p-2" required>
                        <option value="new">Nieuw</option>
                        <option value="used">Gebruikt</option>
                        <option value="refurbished">Gereviseerd</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="image">Afbeelding</label>
                    <input type="file" name="image" id="image" class="block w-full border p-2" accept="image/*">
                </div>

                <div class="mt-4">
                    <label for="expires_at">Verloopt op</label>
                    <input type="date" name="expires_at" id="expires_at" class="block w-full border p-2" required>
                </div>
                <div>
                    Instellingen voor slijtage
                </div>
                <div class="mt-4">
                    <label for="wear_rate">Slijtagesnelheid (0-1)</label>
                    <input type="number" name="wear_rate" id="wear_rate" class="block w-full border p-2" step="0.01" min="0" max="1" required>
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

                <div class="mt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Plaatsen</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

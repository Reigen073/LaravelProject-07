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
                <div class="mt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Plaatsen</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

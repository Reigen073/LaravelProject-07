<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nieuwe Advertentie
        </h2>
    </x-slot>

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
                    <label for="qr_code">QR Code</label>
                    <input type="text" name="qr_code" id="qr_code" class="block w-full border p-2">
                </div>

                <div class="mt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Plaatsen</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

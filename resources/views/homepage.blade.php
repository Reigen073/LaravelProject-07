<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">De Bazaar</h2>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">Laatste Advertenties</h2>
            <div class="flex items-center space-x-4">
                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-600 font-bold hover:underline">
                        Logout
                    </button>
                </form>
                @endauth
                
                @guest
                <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">
                    Login
                </a>
                <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">
                    Register
                </a>
                @endguest
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Filter and Sort Form -->
            <form method="GET" action="{{ route('homepage') }}" class="mb-6 flex justify-between items-center">
                <div class="flex space-x-4">
                    <!-- Category Filter -->
                    <div>
                        <label for="category" class="font-semibold text-gray-700">Categorie</label>
                        <select name="category" id="category" class="border p-2">
                            <option value="">Alle</option>
                            <option value="games" {{ request('category') == 'games' ? 'selected' : '' }}>Games</option>
                            <option value="household" {{ request('category') == 'household' ? 'selected' : '' }}>Huishoud</option>
                            <option value="outdoor" {{ request('category') == 'outdoor' ? 'selected' : '' }}>Buiten</option>
                        </select>
                    </div>

                    <!-- Condition Filter -->
                    <div>
                        <label for="condition" class="font-semibold text-gray-700">Conditie</label>
                        <select name="condition" id="condition" class="border p-2">
                            <option value="">Alle</option>
                            <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>Nieuw</option>
                            <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>Gebruikt</option>
                            <option value="refurbished" {{ request('condition') == 'refurbished' ? 'selected' : '' }}>Gereviseerd</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="font-semibold text-gray-700">Status</label>
                        <select name="status" id="status" class="border p-2">
                            <option value="">Alle</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Beschikbaar</option>
                            <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Verhuurd</option>
                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Verkocht</option>
                        </select>
                    </div>

                    <!-- Sort By Filter -->
                    <div>
                        <label for="sort" class="font-semibold text-gray-700">Sorteren op</label>
                        <select name="sort" id="sort" class="border p-2">
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prijs: Laag naar Hoog</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prijs: Hoog naar Laag</option>
                            <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Titel A-Z</option>
                            <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Titel Z-A</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filteren</button>
            </form>

            <!-- Advertisements -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($advertisements as $ad)
                    <a href="{{ route('advertisements.info', $ad->id) }}" class="block">
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden p-6 transition-transform transform hover:scale-105">
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $ad->title }}</h2>
                            <p class="text-gray-700 mb-3">{{ $ad->description }}</p>
                            <p class="text-lg font-bold">€{{ number_format($ad->price, 2, ',', '.') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="mt-6">
                {{ $advertisements->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</x-app-layout>

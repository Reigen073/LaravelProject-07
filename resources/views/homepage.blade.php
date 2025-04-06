<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">De Bazaar</h2>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">{{ __('messages.latest_adverts') }}</h2>
            <div class="flex items-center space-x-4">
                @auth
                <a href="{{ route('profile.edit', Auth::user()->id) }}" class="text-blue-600 font-bold hover:underline"> 
                    Profiel
                </a>
                <a href="{{ route('dashboard') }}" class="text-blue-600 font-bold hover:underline">
                    Dashboard
                </a>


                <form action="{{ route('lang.switch', 'en') }}" method="GET" class="inline">
                    <button type="submit" class="text-sm text-gray-700 hover:underline">EN</button>
                </form>
                <form action="{{ route('lang.switch', 'nl') }}" method="GET" class="inline ml-2">
                    <button type="submit" class="text-sm text-gray-700 hover:underline">NL</button>
                </form>
                
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
            <form method="GET" action="{{ route('homepage') }}" class="mb-6 flex justify-between items-center">
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
                            <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>{{__('messages.title_asc')}}</option>
                            <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>{{__('messages.title_desc')}}</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ __('messages.filter') }}</button>
            </form>

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

            <div class="mt-6">
                {{ $advertisements->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</x-app-layout>

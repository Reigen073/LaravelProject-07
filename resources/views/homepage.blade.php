<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                De Bazaar
            </h2>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                Laatste Advertenties
            </h2>
            <div class="flex items-center space-x-4">
                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-600 font-bold hover:underline">
                        Logout
                    </button>
                </form>
                    <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
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
        </div>
    </div>
</x-app-layout>

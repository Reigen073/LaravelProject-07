<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight text-center">
            Advertentie Details
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden p-6">
                <div class="">
                    <a href="{{ url()->previous() }}" class="text-blue-500 hover:underline">← Terug naar overzicht</a>
                </div>
                @if ($advertisement->image)
                    <img src="{{ asset('storage/' . $advertisement->image) }}" 
                        alt="{{ $advertisement->title }}" 
                        class="w-full h-48 object-contain bg-gray-200 rounded-lg mb-4">
                @else
                    <img
                        class="w-full h-48 object-contain bg-gray-200 rounded-lg mb-4">
                @endif
                <h1 class="text-3xl font-bold text-gray-900 mb-4 mt-2">{{ $advertisement->title }}</h1>
                <p class="text-gray-700 mb-4">{{ $advertisement->description }}</p>
                <p class="text-2xl font-bol">€{{ number_format($advertisement->price, 2, ',', '.') }}</p>
                
                <p class="text-sm text-gray-500 mt-4">Categorie: <span class="font-medium">{{ $advertisement->category }}</span></p>
                <p class="text-sm text-gray-500 mt-1">Status: <span class="font-medium">{{ ucfirst($advertisement->status) }}</span></p>
                <p class="text-sm text-gray-500 mt-1">Condition: <span class="font-medium">{{ $advertisement->condition }}</span></p>

                @if($advertisement->user)
                    <p class="text-sm text-gray-500 mt-1">Geplaatst door: <span class="font-medium">{{ $advertisement->user->name }}</span></p>
                @endif
                @auth
                <form action="{{ route('advertisements.favorite', $advertisement->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="text-red-500 font-bold">
                        @if(auth()->user()->favorites->contains($advertisement->id))
                            ❤️ Verwijder Favoriet
                        @else
                            🤍 Toevoegen aan Favorieten
                        @endif
                    </button>
                </form>
            @endauth
            </div>
        </div>
    </div>
</x-app-layout>

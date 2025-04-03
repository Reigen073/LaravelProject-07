<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight text-center">
            Advertentie Details
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden p-6">
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

                <p class="text-sm text-gray-500 mt-4">Type: <span class="font-medium">{{ ucfirst($advertisement->type) }}</span></p>
                
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
                        @if(auth()->user()->favorites && auth()->user()->favorites->contains($advertisement->id))
                            ❤️ Verwijder Favoriet
                        @else
                            🤍 Toevoegen aan Favorieten
                        @endif
                    </button>
                </form>
            @endauth
                <p class="text-sm text-gray-500 mt-1">QR-Code: <span class="font-medium">{!! $advertisement->qr_code !!}</span></p>
                
                @if($advertisement->status === 'available' && $advertisement->type === 'buy')
                    <form method="POST" action="{{ route('advertisements.buy', $advertisement->id) }}" 
                        class="mt-4">
                        @csrf @method('POST')
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Koop
                        </button>
                    </form>
                @elseif($advertisement->status === 'available' && $advertisement->type === 'rent')
                    <form method="POST" action="{{ route('advertisements.rent', $advertisement->id) }}" 
                        class="mt-4">
                        @csrf @method('POST')
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Huur
                        </button>
                    </form>
                @elseif($advertisement->status === 'available' && $advertisement->type === 'bidding')
                <form method="POST" action="{{ route('advertisements.bidding', $advertisement->id) }}" class="mt-4">
                    @csrf
                    <input type="number" name="bid_amount" step="0.01" min="0.01" required 
                        class="border border-gray-300 p-2 rounded w-full" 
                        placeholder="Voer je bod in (€)">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">
                        Bied
                    </button>
                </form>
                @endif
                @if ($advertisement->relatedAdvertisements->count())
                    <h3 class="text-xl font-semibold mt-6 mb-4">Gerelateerde Advertenties</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($advertisement->relatedAdvertisements as $related)
                            <a href="{{ route('advertisements.info', $related->id) }}" class="block bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                @if ($related->image)
                                    <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}" class="w-full h-32 object-cover">
                                @else
                                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center text-gray-500">Geen afbeelding</div>
                                @endif
                                <div class="p-4">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $related->title }}</h4>
                                    <p class="text-sm text-gray-600">€{{ number_format($related->price, 2, ',', '.') }}</p>
                                </div>
                                @if($related->status === 'available' && $related->type === 'buy')
                                <form action="{{ route('advertisements.buy', ['advertisement' => $advertisement->id, 'advertisement2' => $related->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2">Koop deze 2 items samen</button>
                                    </form>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
                <form method="POST" action="{{ route('reviews.store', $advertisement->id) }}" class="mt-4 bg-white rounded-2xl shadow-md p-4 border border-gray-200">
                    @csrf
                    <textarea name="comment" class="border p-2 w-full rounded-lg" placeholder="Schrijf een review..." required></textarea>
                    <input type="hidden" name="type" value="advertisement">
                    <select name="rating" class="border p-2 mt-2 w-full rounded-lg" required>
                        <option value="1">1 - Slecht</option>
                        <option value="2">2 - Matig</option>
                        <option value="3">3 - Goed</option>
                        <option value="4">4 - Zeer goed</option>
                        <option value="5">5 - Uitstekend</option>
                    </select>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mt-2">
                        Review Plaatsen
                    </button>
                </form>
                @foreach($advertisement->reviews as $review)
                    <div class="bg-white rounded-2xl shadow-md p-4 mt-4 border border-gray-200">
                        <p class="text-sm text-gray-500">Gepubliceerd door: <span class="font-medium">{{ $review->user->name }}</span></p>
                        <p class="text-lg font-semibold text-gray-900">Rating: <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span></p>
                        <p class="text-gray-700 mt-2">{{ $review->comment }}</p>
                    </div>
                @endforeach
        </div>
    </div>
</x-app-layout>

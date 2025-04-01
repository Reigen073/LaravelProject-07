<x-app-layout>
    <x-slot name="header">
        <h2 class="flex justify-between font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
            
            <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
               href="{{ route('advertisements.create') }}">
                {{ __('Maak advertenties') }}
            </a>
            <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
               href="{{ route('advertisements.agenda') }}">
                {{ __('Bekijk advertenties in agenda') }}
            </a>
            @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-600 font-bold hover:underline">
                    Logout
                </button>
            </form>
            @endauth
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                Welcome, {{ auth()->user()->name }}!
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Jouw Advertenties</h3>
                @if ($advertisements->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($advertisements as $advertisement)
                            <div class="border p-4 rounded-lg shadow-md">
                            @if ($advertisement->image)
                                <img src="{{ asset('storage/' . $advertisement->image) }}" 
                                    alt="{{ $advertisement->title }}" 
                                    class="w-full h-48 object-contain bg-gray-200 rounded-lg mb-4">
                            @else
                                <img
                                    class="w-full h-48 object-contain bg-gray-200 rounded-lg mb-4">
                            @endif

                                <h4 class="font-bold text-lg">{{ $advertisement->title }}</h4>
                                <p class="text-gray-700">{{ Str::limit($advertisement->description, 100) }}</p>
                                <p class="text-gray-900 font-semibold">€{{ number_format($advertisement->price, 2) }}</p>
                                <div class="mt-4">
                                    <a href="{{ route('advertisements.info', $advertisement->id) }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Bekijk advertentie</a>
                                @if(auth()->id() === $advertisement->user_id)
                                    <a href="{{ route('advertisements.edit', $advertisement->id) }}" 
                                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                            Bewerken
                                    </a>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>Je hebt nog geen advertenties geplaatst.</p>
                @endif
            </div>
        </div>
    </div>
    
</x-app-layout>

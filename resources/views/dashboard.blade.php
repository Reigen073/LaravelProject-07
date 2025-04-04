<x-app-layout>
    <x-slot name="header">
        <h2 class="flex justify-between font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
            
            @auth
                @if (in_array(auth()->user()->role, ['particulier_adverteerder', 'zakelijke_adverteerder']))
                    <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                       href="{{ route('advertisements.create') }}">
                        {{ __('Maak advertenties') }}
                    </a>
                @endif
            @endauth
            
            <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
               href="{{ route('advertisements.agenda') }}">
                {{ __('Bekijk advertenties in agenda') }}
            </a>

            <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
               href="{{ route('advertisements.history') }}">
                {{ __('Gekochte producten') }}
            </a>

            @auth
                @if (in_array(auth()->user()->role, ['particulier_adverteerder', 'zakelijke_adverteerder']))
                    <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                       href="{{ route('returns.index') }}">
                        {{ __('Retourverzoeken') }}
                    </a>
                @endif
            @endauth

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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div id="intro-section"class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Introductie</h3>
                <p class="text-gray-700 mb-6">
                    Welkom op je dashboard! Hier kun je je advertenties beheren, je favoriete advertenties bekijken en meer. Gebruik de onderstaande knoppen om advertenties te plaatsen, favorieten te beheren of retourverzoeken in te dienen. Als je je dashboard wilt aanpassen, klik dan op de instellingenknop.
                </p>
                </div>
                <div id="image-section"class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Image</h3>
                    <img src="{{ asset('images/sky.jpg') }}" alt="Sky Image">
                </div>
                <div id="ads-section" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Jouw Advertenties</h3>
                    @if ($advertisements->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($advertisements as $advertisement)
                                <div class="border p-4 rounded-lg shadow-md">
                                    @if ($advertisement->image)
                                        <img src="{{ asset('storage/' . $advertisement->image) }}" 
                                            alt="{{ $advertisement->title }}" 
                                            class="w-full h-48 object-contain bg-gray-200 rounded-lg mb-4">
                                    @endif
                                    <h4 class="font-bold text-lg">{{ $advertisement->title }}</h4>
                                    <p class="text-gray-700">{{ Str::limit($advertisement->description, 100) }}</p>
                                    <p class="text-gray-900 font-semibold">€{{ number_format($advertisement->price, 2) }}</p>
                                    <div class="mt-4 flex gap-2">
                                        <a href="{{ route('advertisements.info', $advertisement->id) }}"
                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Bekijk advertentie</a>
                                        @if(auth()->check() && auth()->id() === $advertisement->user_id)
                                            <a href="{{ route('advertisements.edit', $advertisement->id) }}" 
                                                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                                Bewerken
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>Je hebt nog geen advertenties geplaatst.</p>
                    @endif
                </div>

                <div id="favorites-section" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Jouw Favoriete Advertenties</h3>
                    @auth
                        @if (auth()->user()->favorites && auth()->user()->favorites->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach (auth()->user()->favorites as $advertisement)
                                    <div class="border p-4 rounded-lg shadow-md">
                                        <h4 class="font-bold text-lg">{{ $advertisement->title }}</h4>
                                        <p class="text-gray-700">{{ Str::limit($advertisement->description, 100) }}</p>
                                        <p class="text-gray-900 font-semibold">€{{ number_format($advertisement->price, 2) }}</p>
                                        <div class="mt-4">
                                            <a href="{{ route('advertisements.info', $advertisement->id) }}"
                                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Bekijk advertentie</a>
                                            <form action="{{ route('advertisements.favorite', $advertisement->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-500 font-bold mt-4">
                                                    ❌ Verwijder uit Favorieten
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>Je hebt nog geen favoriete advertenties.</p>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Customize Dashboard Button -->
    <div class="flex justify-end pr-6 mb-4">
        <button id="customize-btn"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            ⚙️ Customize Dashboard
        </button>
    </div>

    <!-- Dashboard Settings Modal -->
    <div id="dashboard-settings-modal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-lg space-y-4">
            <h2 class="text-xl font-bold">Dashboard Instellingen</h2>

            <form id="dashboard-settings-form">
                @csrf

                <label class="block">
                    <input type="checkbox" name="show_ads" id="show_ads"> Toon Advertenties
                </label>

                <label class="block">
                    <input type="checkbox" name="show_favorites" id="show_favorites"> Toon Favorieten
                </label>
                <label class="block">
                    <input type="checkbox" name="show_intro" id="show_intro"> Toon Introductie
                </label>
                <label class="block">
                    <input type="checkbox" name="show_image" id="show_image"> Toon Afbeeldingen
                </label>
                
                <label class="block mt-4">
                    Achtergrondkleur:
                    <input type="color" name="bg_color" id="bg_color" class="ml-2">
                </label>

                <label class="block mt-4">
                    Tekstkleur:
                    <input type="color" name="text_color" id="text_color" class="ml-2">
                </label>

                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" id="close-settings" class="bg-gray-500 text-white px-4 py-2 rounded">Sluiten</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Opslaan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JS Settings Logic -->
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('dashboard-settings-modal');
    const customizeBtn = document.getElementById('customize-btn');
    const closeBtn = document.getElementById('close-settings');
    const form = document.getElementById('dashboard-settings-form');

    const adsSection = document.getElementById('ads-section');
    const favoritesSection = document.getElementById('favorites-section');
    const introSection = document.getElementById('intro-section'); // Add the reference here
    const imageSection = document.getElementById('image-section'); // Add reference to the image section

    customizeBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        loadSettings();
    });

    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const payload = {
            show_ads: document.getElementById('show_ads').checked,
            show_favorites: document.getElementById('show_favorites').checked,
            show_intro: document.getElementById('show_intro').checked, 
            show_image: document.getElementById('show_image').checked, // Image visibility
            bg_color: document.getElementById('bg_color').value,
            text_color: document.getElementById('text_color').value,
        };

        const response = await fetch("{{ route('dashboard.settings.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify(payload)
        });

        if (response.ok) {
            applySettings(payload);
            modal.classList.add('hidden');
        } else {
            alert('Opslaan mislukt');
        }
    });

    async function loadSettings() {
        const response = await fetch("{{ route('dashboard.settings.fetch') }}");
        const settings = await response.json();
        document.getElementById('show_intro').checked = settings.show_intro ?? true; // Load intro visibility setting
        document.getElementById('show_ads').checked = settings.show_ads ?? true;
        document.getElementById('show_favorites').checked = settings.show_favorites ?? true;
        document.getElementById('show_image').checked = settings.show_image ?? true;
        document.getElementById('bg_color').value = settings.bg_color ?? '#ffffff';
        document.getElementById('text_color').value = settings.text_color ?? '#000000';

        applySettings(settings);
    }

    function applySettings(settings) {
        if (adsSection) {
            adsSection.style.display = settings.show_ads ? 'block' : 'none';
            adsSection.style.backgroundColor = settings.bg_color;
            adsSection.style.color = settings.text_color;
        }

        if (favoritesSection) {
            favoritesSection.style.display = settings.show_favorites ? 'block' : 'none';
            favoritesSection.style.backgroundColor = settings.bg_color;
            favoritesSection.style.color = settings.text_color;
        }

        if (introSection) { // Apply the intro section visibility
            introSection.style.display = settings.show_intro ? 'block' : 'none';
            introSection.style.backgroundColor = settings.bg_color;
            introSection.style.color = settings.text_color;
        }
        if (imageSection) {
        imageSection.style.display = settings.show_image ? 'block' : 'none';  // Show or hide the image section based on the setting
        imageSection.style.backgroundColor = settings.bg_color;
        imageSection.style.color = settings.text_color;
    }
    }

    loadSettings(); // apply settings on first load
});

    </script>

</x-app-layout>

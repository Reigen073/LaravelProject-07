<x-app-layout>
    <x-slot name="header">
        <h2 class="flex justify-between font-semibold text-xl text-gray-800 leading-tight space-x-2">
            {{ __('Dashboard') }}

            <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm"
               href="{{ route('homepage') }}">
                {{ __('messages.homepage') }}
            </a>
            
            @auth
                @if (in_array(auth()->user()->role, ['particulier_adverteerder', 'zakelijke_adverteerder', 'admin']))
                    <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm"
                       href="{{ route('advertisements.create') }}">
                        {{ __('messages.create_advert') }}
                    </a>
                @endif
            @endauth
            
            <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm"
               href="{{ route('advertisements.agenda') }}">
                {{ __('messages.show_advert') }}
            </a>
    
            <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm"
               href="{{ route('advertisements.history') }}">
                {{ __('messages.bought_products') }}
            </a>
    
            @auth
                @if (in_array(auth()->user()->role, ['particulier_adverteerder', 'zakelijke_adverteerder', 'admin']))
                    <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm"
                       href="{{ route('returns.index') }}">
                        {{ __('messages.retour_requests') }}
                    </a>
                @endif
            @endauth
    
            @auth
                @if (auth()->user()->role === 'admin')
                    <a class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm"
                       href="http://laravelproject.test/admin/contracts">
                        {{ __('messages.admin_contracts') }}
                    </a>
                @endif
            @endauth
            <form action="{{ route('lang.switch', 'en') }}" method="GET" class="inline">
                <button type="submit" class="text-sm text-gray-700 hover:underline">EN</button>
            </form>
            <form action="{{ route('lang.switch', 'nl') }}" method="GET" class="inline ml-2">
                <button type="submit" class="text-sm text-gray-700 hover:underline">NL</button>
            </form>
    
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
                <h3 class="text-lg font-semibold mb-4">{{ __('messages.introduction') }}</h3>
                <p class="text-gray-700 mb-6">
                    Welkom op je dashboard! Hier kun je je advertenties beheren, je favoriete advertenties bekijken en meer. Gebruik de onderstaande knoppen om advertenties te plaatsen, favorieten te beheren of retourverzoeken in te dienen. Als je je dashboard wilt aanpassen, klik dan op de instellingenknop.
                </p>
                </div>
                <div id="image-section"class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">{{ __('messages.image') }}</h3>
                    <img src="{{ asset('images/sky.jpg') }}" alt="Sky Image">
                </div>
                <div id="ads-section" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">{{ __('messages.your_adverts') }}</h3>
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
                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ __('messages.show_advert') }}</a>
                                        @if(auth()->check() && auth()->id() === $advertisement->user_id)
                                            <a href="{{ route('advertisements.edit', $advertisement->id) }}" 
                                                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                                {{ __('messages.edit') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>{{ __('messages.no_adverts') }}.</p>
                    @endif
                </div>

                <div id="favorites-section" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">{{ __('messages.your_favorite_advertisements') }}</h3>
                    @auth
                        @if (auth()->user()->favorites && auth()->user()->favorites->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($favoriteAdvertisements as $advertisement)
                                    <div class="border p-4 rounded-lg shadow-md">
                                        <h4 class="font-bold text-lg">{{ $advertisement->title }}</h4>
                                        <p class="text-gray-700">{{ Str::limit($advertisement->description, 100) }}</p>
                                        <p class="text-gray-900 font-semibold">€{{ number_format($advertisement->price, 2) }}</p>
                                        <div class="mt-4">
                                            <a href="{{ route('advertisements.info', $advertisement->id) }}"
                                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ __('messages.show_advert') }}</a>
                                            <form action="{{ route('advertisements.favorite', $advertisement->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-500 font-bold mt-4">
                                                    ❌ {{ __('messages.remove_favorite') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>{{ __('messages.no_favorite_adverts') }}</p>
                        @endif
                    @endauth
                    
                </div>
                <div id="CustomLink-section" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form action="{{ route('custom-link.store') }}" method="POST">
                        @csrf
                        <h3 class="text-lg font-semibold mb-4">{{ __('messages.link_name') }}</h3>
                        <input type="text" name="link_name" id="link_name" required>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                           {{ __('messages.save_link') }}
                        </button>
                    </form>
                
                    @if(session('link_name'))
                        <p class="text-green-500 mt-4">
                           {{ __("messages.your_link_is") }} <a href="{{ url(session('link_name')) }}" class="text-green-500">{{ url(session('link_name')) }}</a>
                        </p>
                    @endif
                </div>
                    <!-- next -->
                    <div id="contract-section" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">{{__('messages.your_contracts')}}</h3>
                            @auth
                            @if ($contracts->isNotEmpty())
                                <ul class="space-y-2">
                                    @foreach ($contracts as $contract)
                                        <li class="bg-gray-100 p-4 rounded shadow flex justify-between items-center">
                                            <span>Contract ID: {{ $contract->id }}</span>
                                            <a href="{{ asset('storage/' . $contract->file_path) }}" target="_blank"
                                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                📄 {{ __('messages.show_advert') }}
                                            </a>

                                            <a href="{{ asset('storage/' . $contract->file_path) }}" 
                                               download="{{ basename($contract->file_path) }}"
                                               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                📤 {{ __('messages.download_contract') }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>{{ __('messages.no_contracts') }}</p>
                            @endif
                        @endauth
                        
                            </div>
                        </div>
                        
                    </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end pr-6 mb-4">
        <button id="customize-btn"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            ⚙️ {{ __('messages.customize_dashboard') }}
        </button>
    </div>

    <div id="dashboard-settings-modal" class="fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-lg space-y-4">
            <h2 class="text-xl font-bold">{{__('messages.dashboard_settings')}}</h2>

            <form id="dashboard-settings-form">
                @csrf

                <label class="block">
                    <input type="checkbox" name="show_ads" id="show_ads"> {{ __('messages.show_adverts') }}
                </label>

                <label class="block">
                    <input type="checkbox" name="show_favorites" id="show_favorites"> {{ __('messages.show_favorites') }}
                </label>
                <label class="block">
                    <input type="checkbox" name="show_intro" id="show_intro"> {{ __('messages.show_instructions') }}
                </label>
                <label class="block">
                    <input type="checkbox" name="show_image" id="show_image"> {{ __('messages.show_images') }}
                </label>
                <label class="block">
                    <input type="checkbox" name="show_custom_link" id="show_custom_link"> {{ __('messages.show_custom_link_section') }}
                </label>
                <label class="block">
                    <input type="checkbox" name="show_contracts" id="show_contracts"> {{ __('messages.show_contracts') }}
                </label>
                
                <label class="block mt-4">
                    {{ __('messages.background_color') }}:
                    <input type="color" name="bg_color" id="bg_color" class="ml-2">
                </label>

                <label class="block mt-4">
                    {{ __('messages.text_color') }}:
                    <input type="color" name="text_color" id="text_color" class="ml-2">
                </label>

                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" id="close-settings" class="bg-gray-500 text-white px-4 py-2 rounded">{{ __('messages.close') }}</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('messages.save') }}</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('dashboard-settings-modal');
            const customizeBtn = document.getElementById('customize-btn');
            const closeBtn = document.getElementById('close-settings');
            const form = document.getElementById('dashboard-settings-form');
            const contractSection = document.getElementById('contract-section');

            const adsSection = document.getElementById('ads-section');
            const favoritesSection = document.getElementById('favorites-section');
            const introSection = document.getElementById('intro-section');
            const imageSection = document.getElementById('image-section');
            const customLinkSection = document.getElementById('CustomLink-section');

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
                    show_image: document.getElementById('show_image').checked,
                    show_custom_link: document.getElementById('show_custom_link').checked,
                    show_contracts: document.getElementById('show_contracts').checked,
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
                document.getElementById('show_intro').checked = settings.show_intro ?? true;
                document.getElementById('show_ads').checked = settings.show_ads ?? true;
                document.getElementById('show_favorites').checked = settings.show_favorites ?? true;
                document.getElementById('show_image').checked = settings.show_image ?? true;
                document.getElementById('CustomLink-section').checked = settings.show_custom_link ?? true;
                document.getElementById('show_contracts').checked = settings.show_contracts ?? true;

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

                if (introSection) {
                    introSection.style.display = settings.show_intro ? 'block' : 'none';
                    introSection.style.backgroundColor = settings.bg_color;
                    introSection.style.color = settings.text_color;
                }
                if (imageSection) {
                    imageSection.style.display = settings.show_image ? 'block' : 'none';
                    imageSection.style.backgroundColor = settings.bg_color;
                    imageSection.style.color = settings.text_color;
                }
                if (customLinkSection) {
                    customLinkSection.style.display = settings.show_custom_link ? 'block' : 'none';
                    customLinkSection.style.backgroundColor = settings.bg_color;
                    customLinkSection.style.color = settings.text_color;
                }
                if (contractSection) 
                {
                contractSection.style.display = settings.show_contracts ? 'block' : 'none';
                contractSection.style.backgroundColor = settings.bg_color;
                contractSection.style.color = settings.text_color;
                }
            }

            loadSettings();
        });
    </script>
</x-app-layout>

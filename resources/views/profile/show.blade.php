<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight text-center">
            {{ __('messages.user_profile') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden p-6">
                <div class="flex flex-col items-center text-center">
                    <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-4xl font-bold uppercase">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mt-4">{{ $user->name }}</h1>
                    <p class="text-gray-700 mt-2">{{ __('messages.email') }}: {{ $user->email }}</p>
                    <p class="text-gray-500 mt-1">{{ __('messages.member_since') }}: {{ $user->created_at->format('d-m-Y') }}</p>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-xl font-semibold text-gray-900">{{ __('messages.user_ads', ['name' => $user->name]) }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
                    @foreach($user->advertisements as $advertisement)
                        <div class="bg-white rounded-2xl shadow-md overflow-hidden p-4">
                            @if ($advertisement->image)
                                <img src="{{ asset('storage/' . $advertisement->image) }}" 
                                     alt="{{ $advertisement->title }}" 
                                     class="w-full h-32 object-contain bg-gray-200 rounded-lg">
                            @endif
                            <h2 class="text-lg font-bold text-gray-900 mt-2">{{ $advertisement->title }}</h2>
                            <p class="text-gray-700 mt-1">€{{ number_format($advertisement->price, 2, ',', '.') }}</p>
                            <a href="{{ route('advertisements.info', $advertisement->id) }}" 
                               class="block mt-2 text-blue-500 hover:underline">
                                {{ __('messages.view_ad') }}
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    <h3 class="text-xl font-semibold text-gray-900">{{ __('messages.reviews_about', ['name' => $user->name]) }}</h3>
                    <form method="POST" action="{{ route('reviews.store', $user->id) }}" class="mt-4 bg-white rounded-2xl shadow-md p-4 border border-gray-200">
                        @csrf
                        <textarea name="comment" class="border p-2 w-full rounded-lg" placeholder="{{ __('messages.write_review') }}" required></textarea>
                        <input type="hidden" name="type" value="advertiser">
                        <select name="rating" class="border p-2 mt-2 w-full rounded-lg" required>
                            <option value="1">1 - {{ __('messages.rating_1') }}</option>
                            <option value="2">2 - {{ __('messages.rating_2') }}</option>
                            <option value="3">3 - {{ __('messages.rating_3') }}</option>
                            <option value="4">4 - {{ __('messages.rating_4') }}</option>
                            <option value="5">5 - {{ __('messages.rating_5') }}</option>
                        </select>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mt-2">
                            {{ __('messages.submit_review') }}
                        </button>
                    </form>
                    @foreach($reviews as $review)
                        <div class="bg-white rounded-2xl shadow-md p-4 mt-4 border border-gray-200">
                            <p class="text-sm text-gray-500">{{ __('messages.posted_by') }}: <span class="font-medium">{{ $review->user->name }}</span></p>
                            <p class="text-lg font-semibold text-gray-900">{{ __('messages.rating') }}: <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span></p>
                            <p class="text-gray-700 mt-2">{{ $review->comment }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">Mijn Advertentie Agenda</h2>
    </x-slot>

    <div class="container mx-auto p-6">
        @if(isset($advertisements))
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-4">Gehuurde Advertenties</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">Titel</th>
                                <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">Verloopt op</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($advertisements as $advertisement)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-gray-800">{{ $advertisement->title }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($advertisement->expires_at)->format('d-m-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
        <div class="container mx-auto p-6">
        <div class="flex flex-wrap -mx-4">
            <div class="w-full md:w-1/2 px-4">
                <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                    <h3 class="text-xl font-semibold mb-4">Verlopende Advertenties</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">Titel</th>
                                    <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">Verloopt op</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expiringAdvertisements as $advertisement)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 text-gray-800">{{ $advertisement->title }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($advertisement->expires_at)->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-1/2 px-4">
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-4">Verhuurde Producten</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">Titel</th>
                                    <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">Terug op</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rentedAdvertisements as $advertisement)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 text-gray-800">{{ $advertisement->title }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($advertisement->expires_at)->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-1/2 px-4">
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-4">Biedingen</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">Advertentie</th>
                                    <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">Gebruiker</th>
                                    <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">Bod (€)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($biddings as $bidding)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 text-gray-800">{{ $bidding->advertisement->title }}</td>
                                        <td class="px-6 py-4 text-gray-800">{{ $bidding->user->name }}</td>
                                        <td class="px-6 py-4 text-gray-600">€{{ number_format($bidding->bid_amount, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>

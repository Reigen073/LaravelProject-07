<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">Gekochte Producten</h2>
    </x-slot>

    <div class="container mx-auto p-6">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold mb-4">Jouw Gekochte Producten</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">Titel</th>
                            <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">Prijs</th>
                            <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">Gekocht op</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($advertisements as $advertisment)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-800">{{ $advertisment->title }}</td>
                                <td class="px-6 py-4 text-gray-600">€{{ number_format($advertisment->price, 2) }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($advertisment->created_at)->format('d-m-Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">Gekochte Producten</h2>
    </x-slot>
        @if (session('success'))
            <div class="flex justify-center">
                <div class="bg-green-500 text-white p-3 rounded-lg shadow-md text-center w-1/2">
                    {{ session('success') }}
                </div>
            </div>
        @endif
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
                            <th class="px-6 py-3 text-left text-gray-700 font-medium uppercase border-b">Terugbrengen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($advertisements as $advertisment)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-800">{{ $advertisment->title }}</td>
                                <td class="px-6 py-4 text-gray-600">€{{ number_format($advertisment->price, 2) }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($advertisment->created_at)->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 text-gray-600">
                                    <form action="{{ route('returns.store', $advertisment->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div>
                                            <label for="reason" class="block text-gray-700">Reden voor retour:</label>
                                            <textarea name="reason" id="reason" class="w-full border p-2 rounded" required></textarea>
                                        </div>
                                        <div class="mt-2">
                                            <label for="image" class="block text-gray-700">Upload een foto:</label>
                                            <input type="file" name="image" id="image" class="border p-2 rounded">
                                        </div>
                                        <button type="submit" class="mt-3 bg-red-500 text-white px-4 py-2 rounded">Retour aanvragen</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

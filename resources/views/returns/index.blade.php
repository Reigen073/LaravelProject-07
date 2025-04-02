<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Retourverzoeken</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8 grid grid-cols-2 gap-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h4 class="text-md font-semibold mb-2">Gekochte producten</h4>
                @if ($returns->where('advertisement.type', 'buy')->isNotEmpty())
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border">ID</th>
                                <th class="px-4 py-2 border">Gebruiker</th>
                                <th class="px-4 py-2 border">Reden</th>
                                <th class="px-4 py-2 border">Afbeelding</th>
                                <th class="px-4 py-2 border">Status</th>
                                <th class="px-4 py-2 border">Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($returns->where('advertisement.type', 'buy') as $return)
                                <tr>
                                    <td class="border px-4 py-2">{{ $return->id }}</td>
                                    <td class="border px-4 py-2">{{ $return->user->name }}</td>
                                    <td class="border px-4 py-2">{{ $return->reason }}</td>
                                    <td class="border px-4 py-2">
                                        @if($return->image)
                                            <a href="{{ asset('storage/' . $return->image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $return->image) }}" alt="Retour afbeelding" class="w-16 h-16 object-cover cursor-pointer">
                                            </a>
                                        @else
                                            Geen afbeelding
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ ucfirst($return->status) }}</td>
                                    <td class="border px-4 py-2">
                                        @if($return->status == 'pending')
                                            <form action="{{ route('returns.approve', $return->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">Goedkeuren</button>
                                            </form>
                                            <form action="{{ route('returns.reject', $return->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Afkeuren</button>
                                            </form>
                                        @else
                                            <span class="text-gray-600">Geen acties beschikbaar</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Geen retourverzoeken voor gekochte producten.</p>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h4 class="text-md font-semibold mb-2">Gehuurde producten</h4>
                @if ($returns->where('advertisement.type', 'rent')->isNotEmpty())
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border">ID</th>
                                <th class="px-4 py-2 border">Gebruiker</th>
                                <th class="px-4 py-2 border">Reden</th>
                                <th class="px-4 py-2 border">Afbeelding</th>
                                <th class="px-4 py-2 border">Status</th>
                                <th class="px-4 py-2 border">Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($returns->where('advertisement.type', 'rent') as $return)
                                <tr>
                                    <td class="border px-4 py-2">{{ $return->id }}</td>
                                    <td class="border px-4 py-2">{{ $return->user->name }}</td>
                                    <td class="border px-4 py-2">{{ $return->reason }}</td>
                                    <td class="border px-4 py-2">
                                        @if($return->image)
                                            <a href="{{ asset('storage/' . $return->image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $return->image) }}" alt="Retour afbeelding" class="w-16 h-16 object-cover cursor-pointer">
                                            </a>
                                        @else
                                            Geen afbeelding
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ ucfirst($return->status) }}</td>
                                    <td class="border px-4 py-2">
                                        @if($return->status == 'pending')
                                            <form action="{{ route('returns.approve', $return->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Goedkeuren</button>
                                            </form>
                                            <form action="{{ route('returns.reject', $return->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Afkeuren</button>
                                            </form>
                                        @else
                                            <span class="text-gray-600">Geen acties beschikbaar</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Geen retourverzoeken voor gehuurde producten.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
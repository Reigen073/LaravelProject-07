<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registreren
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Naam</label>
                    <input type="text" name="name" id="name" class="block w-full border p-2 mt-1" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="mt-2 text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="block w-full border p-2 mt-1" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="mt-2 text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Wachtwoord</label>
                    <input type="password" name="password" id="password" class="block w-full border p-2 mt-1" required>
                    @error('password')
                        <div class="mt-2 text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Bevestig Wachtwoord</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full border p-2 mt-1" required>
                    @error('password_confirmation')
                        <div class="mt-2 text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div class="mt-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                    <select name="role" id="role" class="block w-full border p-2 mt-1" required>
                        <option value="gebruiker" {{ old('role') == 'gebruiker' ? 'selected' : '' }}>Gebruiker</option>
                        <option value="particulier_adverteerder" {{ old('role') == 'particulier_adverteerder' ? 'selected' : '' }}>Particuliere Adverteerder</option>
                        <option value="zakelijke_adverteerder" {{ old('role') == 'zakelijke_adverteerder' ? 'selected' : '' }}>Zakelijke Adverteerder</option>
                    </select>
                    @error('role')
                        <div class="mt-2 text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Registreren</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

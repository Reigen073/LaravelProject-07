<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.upload_contracts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 text-green-500">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contracts.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-700">{{ __('messages.select_user') }}</label>
                    <select name="user_id" id="user_id" class="block w-full mt-1">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="contract_name" class="block text-sm font-medium text-gray-700">{{ __('contract name') }}</label>
                    <input type="text" name="contract_name" id="contract_name" class="block w-full mt-1" required>
                    @error('contract_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="contract" class="block text-sm font-medium text-gray-700">{{ __('messages.upload_contract') }}</label>
                    <input type="file" name="contract" id="contract" class="block w-full mt-1" required>
                    @error('contract')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror   
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('messages.upload_contract') }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>

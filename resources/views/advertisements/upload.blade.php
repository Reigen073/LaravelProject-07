<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.upload_csv_files') }}
        </h2>
    </x-slot>

    @if(session('error'))
        <div class="flex justify-center">
            <div class="bg-red-500 text-white p-3 rounded-lg shadow-md text-center w-1/2">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="flex justify-center">
            <div class="bg-green-500 text-white p-3 rounded-lg shadow-md text-center w-1/2">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('advertisements.upload.csv') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mt-4">
                    <label for="csv_file" class="block text-lg font-medium text-gray-700">{{ __('messages.select_csv_file') }}</label>
                    <input type="file" name="csv_file" id="csv_file" class="block w-full border p-2" required>
                </div>

                <div class="mt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded w-full">{{ __('messages.upload') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

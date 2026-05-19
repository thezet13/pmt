<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Presentation Month
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('admin.months.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-1">Name</label>
                        <input name="name" value="{{ old('name') }}"
                               class="w-full border-gray-300 rounded"
                               placeholder="May 2026">
                        @error('name')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Year</label>
                        <input name="year" type="number" value="{{ old('year', 2026) }}"
                               class="w-full border-gray-300 rounded">
                        @error('year')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Month number</label>
                        <input name="month" type="number" min="1" max="12" value="{{ old('month', 5) }}"
                               class="w-full border-gray-300 rounded">
                        @error('month')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="px-4 py-2 bg-gray-500 text-white rounded">
                        Save
                    </button>

                    <a href="{{ route('admin.months.index') }}" class="ml-3 text-gray-600">
                        Cancel
                    </a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

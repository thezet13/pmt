<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Slide {{ $slide->slide_number }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('admin.slides.update', $slide) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block mb-1">Slide number</label>
                        <input name="slide_number" type="number" min="1"
                               value="{{ old('slide_number', $slide->slide_number) }}"
                               class="w-full border-gray-300 rounded">
                        @error('slide_number')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Title</label>
                        <input name="title" value="{{ old('title', $slide->title) }}"
                               class="w-full border-gray-300 rounded">
                        @error('title')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1"
                                   @checked(old('is_active', $slide->is_active))>
                            <span>Active</span>
                        </label>
                    </div>

                    <button class="px-4 py-2 bg-gray-500 text-white rounded">
                        Save
                    </button>

                    <a href="{{ route('admin.slides.index') }}" class="ml-3 text-gray-600">
                        Cancel
                    </a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

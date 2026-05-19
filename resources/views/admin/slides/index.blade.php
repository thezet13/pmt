<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin / Slides
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex gap-3">
                <a href="{{ route('admin.slides.create') }}"
                   class="inline-block px-4 py-2 bg-gray-500 text-white rounded">
                    Create Slide
                </a>

                <a href="{{ route('admin.months.index') }}"
                   class="inline-block px-4 py-2 bg-gray-700 text-white rounded">
                    Months
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Slide number</th>
                            <th class="text-left py-2">Title</th>
                            <th class="text-left py-2">Active</th>
                            <th class="text-left py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($slides as $slide)
                            <tr class="border-b">
                                <td class="py-2">Slide {{ $slide->slide_number }}</td>
                                <td class="py-2">{{ $slide->title ?? '-' }}</td>
                                <td class="py-2">
                                    @if($slide->is_active)
                                        <span class="text-green-600 font-bold">Yes</span>
                                    @else
                                        <span class="text-gray-500">No</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    <a href="{{ route('admin.slides.edit', $slide) }}"
                                       class="text-blue-600">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($slides->isEmpty())
                    <p>No slides yet.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Presentation Management Tool
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold mb-2">Current active month</h3>

                @if($activeMonth)
                <p>{{ $activeMonth->name }}</p>
                @else
                <p class="text-red-600">No active month yet.</p>
                @endif
            </div>

            @if($user->isAdmin())
            <div class="mb-6 flex gap-3">
                <a href="{{ route('admin.months.index') }}"
                    class="inline-block px-4 py-2 bg-gray-800 text-white rounded">
                    Manage Months
                </a>

                <a href="{{ route('admin.slides.index') }}"
                    class="inline-block px-4 py-2 bg-gray-800 text-white rounded">
                    Manage Slides
                </a>

                <a href="{{ route('admin.permissions.index') }}"
                    class="inline-block px-4 py-2 bg-gray-800 text-white rounded">
                    User Permissions
                </a>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Available slides</h3>

                @if($slides->count())
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Slide</th>
                            <th class="text-left py-2">Title</th>
                            <th class="text-left py-2">Status</th>
                            <th class="text-left py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($slides as $slide)
                        @php
                        $status = $statuses->get($slide->id);
                        $statusValue = $status?->status ?? 'in_progress';
                        @endphp

                        <tr class="border-b">
                            <td class="py-2">Slide {{ $slide->slide_number }}</td>
                            <td class="py-2">{{ $slide->title ?? '-' }}</td>
                            <td class="py-2">
                                @if($statusValue === 'completed')
                                <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-sm font-bold">
                                    Completed
                                </span>
                                @else
                                <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-sm font-bold">
                                    In progress
                                </span>
                                @endif
                            </td>
                            <td class="py-2">
                                <div class="flex gap-3">
                                    @if($activeMonth)
                                    <a href="{{ route('slides.edit', $slide) }}" class="text-blue-600">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('slides.export', $slide) }}">
                                        @csrf
                                        <button class="text-green-600">
                                            Export Slide
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-gray-400">No active month</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($user->isAdmin() && $slides->count())
                @php
                $completedCount = $slides->filter(function ($slide) use ($statuses) {
                return optional($statuses->get($slide->id))->status === 'completed';
                })->count();

                $totalCount = $slides->count();
                @endphp

                <div class="mt-6 p-4 bg-gray-50 rounded">
                    <p class="font-bold">
                        Progress: {{ $completedCount }} / {{ $totalCount }} slides completed
                    </p>

                    @if($completedCount === $totalCount)
                    <p class="text-green-700 mt-1">
                        Presentation is ready for export.
                    </p>
                    @else
                    <p class="text-gray-600 mt-1">
                        Presentation is not ready yet.
                    </p>
                    @endif
                </div>
                @endif
                @else
                <p>No slides available yet.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

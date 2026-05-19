<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Slide Permissions: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('admin.permissions.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <p class="font-bold">{{ $user->name }}</p>
                        <p class="text-gray-600">{{ $user->email }}</p>
                    </div>

                    <div class="mb-6">
                        <label class="block font-bold mb-3">Allowed slides</label>

                        @foreach($slides as $slide)
                            <label class="flex items-center gap-2 mb-2">
                                <input type="checkbox"
                                       name="slides[]"
                                       value="{{ $slide->id }}"
                                       @checked(in_array($slide->id, $allowedSlideIds))>

                                <span>
                                    Slide {{ $slide->slide_number }}
                                    @if($slide->title)
                                        — {{ $slide->title }}
                                    @endif
                                </span>
                            </label>
                        @endforeach

                        @if($slides->isEmpty())
                            <p>No active slides.</p>
                        @endif
                    </div>

                    <button class="px-4 py-2 bg-gray-500 text-white rounded">
                        Save permissions
                    </button>

                    <a href="{{ route('admin.permissions.index') }}" class="ml-3 text-gray-600">
                        Cancel
                    </a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

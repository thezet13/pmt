<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin / User Slide Permissions
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
                <a href="{{ route('admin.slides.index') }}"
                   class="inline-block px-4 py-2 bg-gray-700 text-white rounded">
                    Slides
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
                            <th class="text-left py-2">User</th>
                            <th class="text-left py-2">Email</th>
                            <th class="text-left py-2">Role</th>
                            <th class="text-left py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="border-b">
                                <td class="py-2">{{ $user->name }}</td>
                                <td class="py-2">{{ $user->email }}</td>
                                <td class="py-2">{{ $user->role }}</td>
                                <td class="py-2">
                                    @if($user->isAdmin())
                                        <span class="text-gray-500">Admin has all slides</span>
                                    @else
                                        <a href="{{ route('admin.permissions.edit', $user) }}"
                                           class="text-blue-600">
                                            Edit permissions
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($users->isEmpty())
                    <p>No users yet.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin / Presentation Months
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('admin.months.create') }}"
                    class="inline-block px-4 py-2 bg-gray-500 text-white rounded">
                    Create Month
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Name</th>
                            <th class="text-left py-2">Year</th>
                            <th class="text-left py-2">Month</th>
                            <th class="text-left py-2">Status</th>
                            <th class="text-left py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($months as $month)
                        <tr class="border-b">
                            <td class="py-2">{{ $month->name }}</td>
                            <td class="py-2">{{ $month->year }}</td>
                            <td class="py-2">{{ $month->month }}</td>
                            <td class="py-2">
                                @if($month->is_active)
                                <span class="text-green-600 font-bold">Active</span>
                                @else
                                <span class="text-gray-500">Inactive</span>
                                @endif
                            </td>
                            <td class="py-2">
                                <div class="flex gap-2">
                                    @if(!$month->is_active)
                                    <form method="POST" action="{{ route('admin.months.activate', $month) }}">
                                        @csrf
                                        <button class="px-3 py-1 bg-gray-800 text-white rounded">
                                            Activate
                                        </button>
                                    </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.months.export', $month) }}">
                                        @csrf
                                        <button class="px-3 py-1 bg-green-600 text-white rounded">
                                            Export
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($months->isEmpty())
                <p>No months yet.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

<div class="mb-6 rounded border bg-white border-gray-200 p-4">

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block mb-1 font-bold text-sm">Başlıq</label>
            <input name="title" value="{{ old('title', $values['title'] ?? '') }}" class="w-full border-gray-300 rounded">
        </div>

        <div>
            <label class="block mb-1 font-bold text-sm">Alt başlıq</label>
            <input name="subtitle" value="{{ old('subtitle', $values['subtitle'] ?? '') }}" class="w-full border-gray-300 rounded">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        @for ($i = 1; $i <= 6; $i++)
            <div class="border rounded p-3 bg-neutral-50">
                <label class="block mb-1 font-bold text-sm">Başlıq {{ $i }}</label>
                <input
                    name="text{{ $i }}h"
                    value="{{ old('text' . $i . 'h', $values['text' . $i . 'h'] ?? '') }}"
                    class="w-full border-gray-300 rounded mb-3"
                >

                <label class="block mb-1 font-bold text-sm">Mətn {{ $i }}</label>
                <textarea
                    name="text{{ $i }}"
                    rows="4"
                    class="w-full border-gray-300 rounded"
                >{{ old('text' . $i, $values['text' . $i] ?? '') }}</textarea>
            </div>
        @endfor
    </div>

</div>

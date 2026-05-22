<div class="w-full flex p-3 bg-neutral-200 rounded-md mb-4 justify-between">
    <div class="grid grid-cols-2 w-full gap-4">
        <div class="mb-4">
            <label class="block mb-1 font-bold text-sm">Başlıq</label>
            <input name="title"
                value="{{ old('title', $values['title'] ?? '') }}"
                class="w-full border-gray-300 rounded">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-bold text-sm">Alt başlıq</label>
            <input name="subtitle"
                value="{{ old('subtitle', $values['subtitle'] ?? '') }}"
                class="w-full border-gray-300 rounded">
        </div>
    </div>
</div>

<div class="mb-6 rounded border bg-white border-gray-200 p-4">
    <div class="grid grid-cols-2 gap-4">

        @for ($i = 1; $i <= 4; $i++)

            <div class="border rounded p-3 bg-neutral-50">

                <label class="block mb-1 font-bold text-sm">
                    Mətn {{ $i }}
                </label>

                <input
                    name="text{{ $i }}"
                    value="{{ old('text' . $i, $values['text' . $i] ?? '') }}"
                    class="w-full border-gray-300 rounded mb-3"
                >

                <label class="block mb-1 font-bold text-sm">
                    Alt mətn {{ $i }}
                </label>

                <textarea
                    name="subtext{{ $i }}"
                    rows="4"
                    class="w-full border-gray-300 rounded"
                >{{ old('subtext' . $i, $values['subtext' . $i] ?? '') }}</textarea>

            </div>

        @endfor

    </div>
</div>

<div class="mb-6 rounded border bg-white border-gray-200 p-4">

    <div class="grid grid-cols-2 gap-4 w-fill">
        @foreach ([
        'es' => 'Ərizə sayı',
        'fm' => 'Fəaliyyət müddəti',
        'kp' => 'Könüllü proqramına cəlb olunub',
        'id' => 'İşə düzəlmə',
        ] as $key => $label)

        <div class="rounded bg-gray-50 p-3 border w-full">
            <label class="block mb-1 font-bold text-sm">{{ $label }}</label>
            <div class="flex gap-2 w-full">
                <div class="w-full">
                    <input name="{{ $key }}"
                        value="{{ old($key, $values[$key] ?? '') }}"
                        class="w-full border-gray-300 rounded mb-3">
                </div>
                <div>
                    <input name="{{ $key }}_r"
                        value="{{ old($key . '_r', $values[$key . '_r'] ?? '') }}"
                        class="w-full border-gray-300 rounded font-bold">
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

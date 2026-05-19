@php
$donutBlock = $values['donut_block'] ?? [];
@endphp

<div class="w-full flex p-3 bg-neutral-200 rounded-md mb-4 justify-between">

    <div class="grid grid-cols-2 w-full gap-4">
        <div class="mb-4">
            <label class="block mb-1 font-bold  text-sm">Başlıq</label>
            <input name="title"
                value="{{ old('title', $values['title'] ?? '') }}"
                class="w-full border-gray-300 rounded">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-bold  text-sm">Alt başlıq</label>
            <input name="subtitle"
                value="{{ old('subtitle', $values['subtitle'] ?? '') }}"
                class="w-full border-gray-300 rounded">
        </div>
    </div>
</div>

<div class="grid grid-cols-3 gap-4 mb-4">
    <div>
        <label class="block mb-1 font-bold  text-sm">2024</label>
        <input name="number_1"
            value="{{ old('number_1', $values['number_1'] ?? '') }}"
            class="w-full border-gray-300 rounded">
    </div>

    <div>
        <label class="block mb-1 font-bold  text-sm">2025</label>
        <input name="number_2"
            value="{{ old('number_2', $values['number_2'] ?? '') }}"
            class="w-full border-gray-300 rounded">
    </div>

    <div>
        <label class="block mb-1 font-bold  text-sm">Kumulyativ</label>
        <input name="number_3"
            value="{{ old('number_3', $values['number_3'] ?? '') }}"
            class="w-full border-gray-300 rounded">
    </div>
</div>

<div class="mb-6 rounded border bg-white border-gray-200 p-4">
    <img
        data-preview-src="{{ url('/slide-10-donut') }}"
        src="{{ url('/slide-10-donut') }}?v={{ time() }}"
        class="w-full"
        alt="Donut preview">
</div>

@include('slides.partials.multi-donut-block10', [
'donutBlock' => $donutBlock,
])

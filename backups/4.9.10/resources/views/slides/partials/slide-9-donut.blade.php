@php
// значения уже приходят с учетом defaults из Controller
$bakuRaw = $values['baku'];
$regionsRaw = $values['regions'];

// убираем пробелы и приводим к числам
$baku = (int) str_replace(' ', '', $bakuRaw);
$regions = (int) str_replace(' ', '', $regionsRaw);

$total = $baku + $regions;

$bakuPct = $total > 0 ? round(($baku / $total) * 100) : 0;
$regionsPct = $total > 0 ? 100 - $bakuPct : 0;

$colors = $values['donut_colors'] ?? [
'baku' => '#1f7eb4',
'regions' => '#20b878',
];
@endphp

<div class="grid grid-cols-2 p-4">

    {{-- INPUTS --}}
    <div class="grid grid-cols-2 gap-4 mb-4">

        <div>
            <label class="block mb-1 font-bold">Bakı</label>
            <div class="flex">
                <input
                type="color"
                name="donut_colors[baku]"
                value="{{ old('donut_colors.baku', $colors['baku']) }}"
                class="h-10 w-10 rounded">
            <input
                type="text"
                name="baku"
                value="{{ old('baku', $values['baku']) }}"
                class="w-full border-gray-300 rounded">
        </div>
        </div>

        <div>
            <label class="block mb-1 font-bold">Regionlar</label>
            <div class="flex">
                <input
                    type="color"
                    name="donut_colors[regions]"
                    value="{{ old('donut_colors.regions', $colors['regions']) }}"
                    class="h-10 w-10 rounded">
                <input
                    type="text"
                    name="regions"
                    value="{{ old('regions', $values['regions']) }}"
                    class="w-full border-gray-300 rounded">
            </div>
        </div>

    </div>

    {{-- COLORS --}}
    <div class="gap-4">

        {{-- PREVIEW --}}
        <div class="flex items-center justify-center gap-6">

            <div class="mb-6 rounded border bg-white border-gray-200 p-4">
                <img
                    data-preview-src="{{ url('/slide-9-donut') }}"
                    src="{{ url('/slide-9-donut') }}?v={{ time() }}"
                    class="w-full"
                    alt="Donut preview">
            </div>


        </div>

    </div>

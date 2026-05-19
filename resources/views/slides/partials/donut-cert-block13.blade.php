@php
    $legend = $donutCert['legend'] ?? [];
    $values = $donutCert['values'] ?? [];

    $total = array_sum(array_map('intval', $values));
@endphp

<div class="bg-white p-4 mb-4">

    <input type="hidden" name="donut_cert[width]" value="{{ $donutCert['width'] ?? 410 }}">
    <input type="hidden" name="donut_cert[height]" value="{{ $donutCert['height'] ?? 470 }}">

    <div class="mb-3">
        <h3 class="font-bold text-sm">Sertifikatlar</h3>
        <p class="text-xs text-gray-500">
            İstifadəçi yalnız sertifikat sayını daxil edir. Faizlər avtomatik hesablanır.
        </p>
    </div>

    <div class="space-y-2">
        @foreach ($legend as $i => $item)
            @php
                $count = (int) old("donut_cert.values.$i", $values[$i] ?? 0);
                $percent = $total > 0 ? round(($count / $total) * 100) : 0;
            @endphp

            <div class="grid grid-cols-[32px_1fr_90px_60px] gap-2 items-center">
                <div>
                    <input
                        type="color"
                        name="donut_cert[legend][{{ $i }}][color]"
                        value="{{ old("donut_cert.legend.$i.color", $item['color'] ?? '#000000') }}"
                        class="h-8 w-8 border-gray-300 rounded">
                </div>

                <input
                    type="text"
                    name="donut_cert[legend][{{ $i }}][label]"
                    value="{{ old("donut_cert.legend.$i.label", $item['label'] ?? '') }}"
                    class="border-gray-300 rounded text-sm">

                <input
                    type="number"
                    min="0"
                    step="1"
                    name="donut_cert[values][{{ $i }}]"
                    value="{{ $count }}"
                    class="ui-num border-gray-300 rounded text-sm">

                <div class="text-sm text-gray-500">
                    {{ $percent }}%
                </div>
            </div>
        @endforeach
    </div>

</div>

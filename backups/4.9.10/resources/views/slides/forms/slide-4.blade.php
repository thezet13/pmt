@php
function pmtAmountToFloat($value) {
$value = str_replace(' ', '', (string) $value);
$value = str_replace(',', '.', $value);
return (float) $value;
}

function pmtFormatAmount($value) {
return number_format((float) $value, 2, ',', ' ');
}

$rows = $values['budget_rows'] ?? [];

$total = collect($rows)->sum(
fn ($row) => pmtAmountToFloat($row['amount'] ?? 0)
);
@endphp

<div class="w-full flex p-3 bg-neutral-200 rounded-md mb-4 justify-between">
    <div class="grid grid-cols-2 w-full gap-4">
        <div class="mb-4">
            <label class="block mb-1 font-bold text-sm">Başlıq</label>
            <input name="title"
                value="{{ old('title', $values['title'] ?? 'Maliyyə fəaliyyətinin icmalı') }}"
                class="w-full border-gray-300 rounded">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-bold text-sm">Alt başlıq</label>
            <input name="subtitle"
                value="{{ old('subtitle', $values['subtitle'] ?? 'xərclər smetası və icrası ilə bağlı vəziyyət') }}"
                class="w-full border-gray-300 rounded">
        </div>
    </div>
</div>

<div class="mb-6 rounded border bg-white border-gray-200 p-4">
    <h3 class="font-bold text-sm mb-3">Yuxarı mətn blokları</h3>

    <div class="grid grid-cols-2 gap-4">
        @for ($i = 1; $i <= 4; $i++)
            <div>
            <label class="block mb-1 font-bold text-sm">Mətn {{ $i }}</label>
            <textarea
                name="text_blocks[{{ $i - 1 }}]"
                rows="3"
                class="w-full border-gray-300 rounded">{{ old("text_blocks." . ($i - 1), $values['text_blocks'][$i - 1] ?? '') }}</textarea>
    </div>
    @endfor
</div>

<p class="text-xs text-gray-500 mt-2">
    Qeyd: rəqəmləri export zamanı avtomatik bold etmək üçün ayrıca renderer-də işləyəcəyik.
</p>
</div>

<div class="mb-6 rounded border bg-white border-gray-200 p-4">
    <h2 class="font-bold text-sm mb-4">Xərclər smetası və onun icrası ilə bağlı detallı məlumatlar cədvəli
    </h2>

    <div class="flex gap-2 mb-2 font-bold text-sm text-gray-600">
        <div style="width:20px" class="">#</div>

        <div style="width:550px">
            Kateqoriya
        </div>

        <div style="width:150px;text-align: center;" class="">
            Smeta məbləği
        </div>

        <div style="width:100px" class="">
            %
        </div>

        <div style="width:150px;text-align: center;" class="">
            Faktiki məbləği
        </div>

        <div style="width:100px" class="">
            %
        </div>
    </div>

    @foreach ($rows as $index => $row)

    @php
    $smetaAmount = pmtAmountToFloat($row['smeta_amount'] ?? 0);
    $factAmount = pmtAmountToFloat($row['fact_amount'] ?? 0);

    $smetaTotal = collect($rows)->sum(
    fn ($r) => pmtAmountToFloat($r['smeta_amount'] ?? 0)
    );

    $factTotal = collect($rows)->sum(
    fn ($r) => pmtAmountToFloat($r['fact_amount'] ?? 0)
    );

    $smetaPercent = $smetaTotal > 0
    ? ($smetaAmount / $smetaTotal) * 100
    : 0;

    $factPercent = $smetaAmount > 0
    ? ($factAmount / $smetaAmount) * 100
    : 0;
    @endphp

    <div class="flex items-center gap-3 mb-3">
        <div style="width:10px" class="shrink-0 text-sm text-gray-500">
            {{ $index + 1 }}
        </div>

        <div style="width:550px">
            <input
                name="budget_rows[{{ $index }}][label]"
                value="{{ old("budget_rows.$index.label", $row['label'] ?? '') }}"
                style="width:500px" class="border-gray-300 rounded text-sm">
        </div>

        <div style="width:140px">
            <input
                name="budget_rows[{{ $index }}][smeta_amount]"
                value="{{ old("budget_rows.$index.smeta_amount", $row['smeta_amount'] ?? '') }}"
                class="js-smeta-amount w-full border-gray-300 rounded text-sm text-right tabular-nums">
        </div>

        <div style="width:100px" class="js-smeta-percent text-sm text-neutral-500  font-semibold text-left tabular-nums">
            {{ number_format($smetaPercent, 2, ',', ' ') }}%
        </div>

        <div style="width:140px">
            <input
                name="budget_rows[{{ $index }}][fact_amount]"
                value="{{ old("budget_rows.$index.fact_amount", $row['fact_amount'] ?? '') }}"
                class="js-fact-amount w-full border-gray-300 rounded text-sm text-right tabular-nums">
        </div>

        <div style="width:100px" class="js-fact-percent text-sm text-neutral-500  font-semibold text-left tabular-nums">
            {{ number_format($factPercent, 2, ',', ' ') }}%
        </div>
    </div>

    @endforeach

    <div class="flex items-center gap-3 mt-4 border-t pt-3 font-bold">
        <div style="width:10px"></div>

        <div style="width:550px; text-align:right">

        </div>

        <div style="width:140px" class="js-smeta-total text-right tabular-nums">
            {{ pmtFormatAmount($smetaTotal) }}
        </div>

        <div style="width:100px" class="js-smeta-total-percent text-neutral-500 tabular-nums">
            100,00%
        </div>

        <div style="width:140px" class="js-fact-total text-right tabular-nums">
            {{ pmtFormatAmount($factTotal) }}
        </div>

        <div style="width:100px" class="js-fact-total-percent text-neutral-500 tabular-nums">
            {{ $smetaTotal > 0
        ? number_format(($factTotal / $smetaTotal) * 100, 2, ',', ' ')
        : '0,00' }}%
        </div>
    </div>
</div>

<div class="mb-6">
    <label class="block mb-1 font-bold text-sm">Qeyd</label>
    <textarea
        name="note"
        rows="3"
        class="w-full border-gray-300 rounded">{{ old('note', $values['note'] ?? 'Agentlik, “DOST” mərkəzləri və Agentliyin təsis etdiyi təsərrüfat cəmiyyətləri üzrə') }}</textarea>
</div>

<script>
    function pmtParseAmount(value) {
        return Number(
            String(value)
            .replace(/\s/g, '')
            .replace(',', '.')
        ) || 0;
    }

    function pmtFormatAmount(value) {
        return value.toLocaleString('ru-RU', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    }

    function pmtFormatPercent(value) {
        return value.toLocaleString('ru-RU', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }) + '%';
    }

    function updateSmetaColumn() {
        const smetaInputs = [...document.querySelectorAll('.js-smeta-amount')];

        const smetaTotal = smetaInputs.reduce((sum, input) => {
            return sum + pmtParseAmount(input.value);
        }, 0);

        document.querySelector('.js-smeta-total').textContent = pmtFormatAmount(smetaTotal);
        document.querySelector('.js-smeta-total-percent').textContent = smetaTotal > 0 ? '100,00%' : '0,00%';

        document.querySelectorAll('.js-smeta-percent').forEach((el, index) => {
            const smetaAmount = pmtParseAmount(smetaInputs[index]?.value || 0);
            const percent = smetaTotal > 0 ? (smetaAmount / smetaTotal) * 100 : 0;

            el.textContent = pmtFormatPercent(percent);
        });
    }

    function updateFactColumn() {
        const smetaInputs = [...document.querySelectorAll('.js-smeta-amount')];
        const factInputs = [...document.querySelectorAll('.js-fact-amount')];

        const smetaTotal = smetaInputs.reduce((sum, input) => {
            return sum + pmtParseAmount(input.value);
        }, 0);

        const factTotal = factInputs.reduce((sum, input) => {
            return sum + pmtParseAmount(input.value);
        }, 0);

        document.querySelector('.js-fact-total').textContent = pmtFormatAmount(factTotal);

        document.querySelectorAll('.js-fact-percent').forEach((el, index) => {
            const smetaAmount = pmtParseAmount(smetaInputs[index]?.value || 0);
            const factAmount = pmtParseAmount(factInputs[index]?.value || 0);

            const percent = smetaAmount > 0 ? (factAmount / smetaAmount) * 100 : 0;

            el.textContent = pmtFormatPercent(percent);
        });

        const totalPercent = smetaTotal > 0 ? (factTotal / smetaTotal) * 100 : 0;
        document.querySelector('.js-fact-total-percent').textContent = pmtFormatPercent(totalPercent);
    }

    document.addEventListener('input', function(event) {
        if (
            !event.target.classList.contains('js-smeta-amount') &&
            !event.target.classList.contains('js-fact-amount')
        ) {
            return;
        }

        updateSmetaColumn();
        updateFactColumn();
    });
</script>

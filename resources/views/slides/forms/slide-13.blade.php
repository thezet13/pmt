@php
$donutCert = $values['donut_cert'] ?? [];
@endphp

<div class="w-full flex p-3 bg-neutral-200 rounded-md mb-4 justify-between">
    <div class="grid w-full gap-4">
        <div class="mb-4">
            <label class="block mb-1 font-bold text-sm">Başlıq</label>
            <input name="title"
                value="{{ old('title', $values['title'] ?? '') }}"
                class="w-full border-gray-300 rounded">
        </div>


    </div>
</div>

@php
$groups = [
'Kadr hərəkəti' => [
'iseqebil' => 'İşə qəbul sayı',
'isdenazad' => 'İşdən azad sayı',
'orta_isci' => 'Orta işçi sayı',
'turnover' => 'Turnover',
],

'Ümumi göstəricilər' => [
'umimi' => 'Ümumi say',
'ortayas' => 'Orta yaş',
'kisi' => 'Kişi',
'qadin' => 'Qadın',
],

'Vakansiyalar' => [
'v_ag' => 'DOST Agentliyi',
'v_ap' => 'DOST Aparat',
'v_me' => 'DOST Aparat',
'v_mmc' => 'DOST MMC-lər',
],

'İşçi sayı' => [
'i_ag' => 'DOST Agentliyi',
'i_ap' => 'DOST Aparat',
'i_me' => 'DOST Aparat',
'i_mmc' => 'DOST MMC-lər',
],

'Stat sayi' => [
's_ag' => 'DOST Agentliyi',
's_ap' => 'DOST Aparat',
's_me' => 'DOST Aparat',
's_mmc' => 'DOST MMC-lər',
],
];
@endphp

{{-- Kadr hərəkəti --}}
<div class="rounded-xl bg-white border border-gray-200 p-4 mb-4 shadow-sm">



    <div class="grid grid-cols-5 gap-4">

        <div class="mb-4">
            <label class="block mb-1 font-bold text-sm">Mətn</label>
            <input name="stat"
                value="{{ old('stat', $values['stat'] ?? '') }}"
                class="w-full border-gray-300 rounded">
        </div>

        @foreach ([
        'iseqebil' => 'İşə qəbul sayı',
        'isdenazad' => 'İşdən azad sayı',
        'orta_isci' => 'Orta işçi sayı',
        'turnover' => 'Turnover',
        ] as $name => $label)

        <div>
            <label class="block mb-1 font-bold text-sm">
                {{ $label }}
            </label>

            <input
                type="{{ $name === 'turnover' ? 'text' : 'number' }}"
                name="{{ $name }}"
                value="{{ old($name, $values[$name] ?? '') }}"
                class="ui-num w-full border-gray-300 rounded">
        </div>

        @endforeach

    </div>
</div>

{{-- Ümumi göstəricilər --}}
<div class="rounded-xl bg-neutral-50 border border-neutral-200 p-4 mb-4 shadow-sm">

    <div class="grid grid-cols-4 gap-4">

        @foreach ([
        'umimi' => 'Ümumi say',
        'ortayas' => 'Orta yaş',
        'kisi' => 'Kişi',
        'qadin' => 'Qadın',
        ] as $name => $label)

        <div>
            <label class="block mb-1 font-bold text-sm">
                {{ $label }}
            </label>

            <input
                type="number"
                name="{{ $name }}"
                value="{{ old($name, $values[$name] ?? '') }}"
                class="ui-num w-full border-gray-300 rounded">
        </div>

        @endforeach

    </div>

</div>
{{-- Vakansiyalar --}}
<div class="grid rounded-xl bg-neutral-50 border border-neutral-200 p-4 mb-4 gap-y-2 shadow-sm">

    <div class="grid grid-cols-5 gap-2">
        <div class="px-2"></div>
        <div class="px-2">DOST Agentliyi</div>
        <div class="px-2">DOST Aparat</div>
        <div class="px-2">DOST Mərkəzləri</div>
        <div class="px-2">DOST MMC-lər</div>

        <div class="text-right p-2">
            Vakansiya sayı
        </div>
        @foreach ([
        'v_ag' => 'DOST Agentliyi',
        'v_ap' => 'DOST Aparat',
        'v_me' => 'DOST Mərkəzləri',
        'v_mmc' => 'DOST MMC-lər',
        ] as $name => $label)
        <div>
            <input
                type="number"
                name="{{ $name }}"
                value="{{ old($name, $values[$name] ?? '') }}"
                class="ui-num w-full border-gray-300 rounded">
        </div>
        @endforeach

        <div class="text-right p-2">
            İşçi sayı
        </div>
        @foreach ([
        'i_ag' => 'DOST Agentliyi',
        'i_ap' => 'DOST Aparat',
        'i_me' => 'DOST Mərkəzləri',
        'i_mmc' => 'DOST MMC-lər',
        ] as $name => $label)
        <div>
            <input
                type="number"
                name="{{ $name }}"
                value="{{ old($name, $values[$name] ?? '') }}"
                class="ui-num w-full border-gray-300 rounded">
        </div>
        @endforeach

        <div class="text-right p-2">
            Ştat sayı
        </div>
        @foreach ([
        's_ag' => 'DOST Agentliyi',
        's_ap' => 'DOST Aparat',
        's_me' => 'DOST Mərkəzləri',
        's_mmc' => 'DOST MMC-lər',
        ] as $name => $label)
        <div>
            <input
                type="number"
                name="{{ $name }}"
                value="{{ old($name, $values[$name] ?? '') }}"
                class="ui-num w-full border-gray-300 rounded">
        </div>
        @endforeach
    </div>
</div>


<div class="grid grid-cols-2 gap-4 rounded border bg-white border-gray-200 p-4">

    <div class="p-4">
        <img
            data-preview-src="{{ url('/slide-13-donut') }}"
            src="{{ url('/slide-13-donut') }}?v={{ time() }}"
            class="mx-auto"
            style="width:410px; max-width:100%;"
            alt="Slide 13 donut preview">
    </div>

    @include('slides.partials.donut-cert-block13', [
    'donutCert' => $donutCert,
    ])
</div>

<div class="mb-6 rounded border bg-white border-gray-200 p-4">
    <label class="block mb-1 font-bold text-sm">Qeyd</label>
    <textarea
        name="qeyd"
        rows="1"
        class="w-full border-gray-300 rounded">{{ old('qeyd', $values['qeyd'] ?? '') }}</textarea>
</div>

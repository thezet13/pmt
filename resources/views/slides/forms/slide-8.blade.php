<div class="space-y-6">


    {{-- Header --}}
    <div class="w-full flex p-3 bg-neutral-200 rounded-md justify-between">
        <div class="grid grid-cols-2 w-full gap-4">
            <div>
                <label class="block mb-1 font-bold  text-sm">Başlıq</label>
                <input
                    type="text"
                    name="title"
                    value="{{ old('title', $values['title']) }}"
                    class="w-full border-gray-300 rounded">
            </div>

            <div>
                <label class="block mb-1 font-bold  text-sm">Alt başlıq</label>
                <input
                    type="text"
                    name="subtitle"
                    value="{{ old('subtitle', $values['subtitle']) }}"
                    class="w-full border-gray-300 rounded">
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">

        <div class="p-4">

            <div>
                <input type="text" name="tedfaiz"
                    value="{{ old('tedfaiz', $values['tedfaiz'] ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>



            <div class="grid grid-cols-3 gap-10 mt-3">

                <div>
                    <label class="block text-sm font-medium">Tədbirlər sayı</label>
                    <input type="text" inputmode="decimal" name="tedbir"
                        value="{{ old('tedbir', $values['tedbir'] ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div class="grid grid-cols-2 gap-1">
                    <div>
                        <label class="block text-sm font-medium">Tam icra</label>
                        <input type="text" inputmode="decimal" name="tr"
                            value="{{ old('tr', $values['tr'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-center">%</label>
                        <input type="text" inputmode="decimal" name="tf"
                            value="{{ old('tf', $values['tf'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-1">
                    <div>
                        <label class="block text-sm font-medium">Qismən icra</label>
                        <input type="text" inputmode="decimal" name="qr"
                            value="{{ old('qr', $values['qr'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>



                    <div>
                        <label class="block text-sm font-medium text-center">%</label>
                        <input type="text" inputmode="decimal" name="qf"
                            value="{{ old('qf', $values['qf'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>






            </div>
            <div class="mt-3">
                <label class="block text-sm font-medium">İcra edilməmiş</label>
                <input type="text" name="icx"
                    value="{{ old('icx', $values['icx'] ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>







            <div class="grid grid-cols-2 gap-4 mt-10">
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium">STRATEJİ MƏQSƏD </label>
                        <input type="text" inputmode="decimal" name="sm"
                            value="{{ old('sm', $values['sm'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">PRİORİTET İSTİQAMƏT</label>
                        <input type="text" inputmode="decimal" name="pi"
                            value="{{ old('pi', $values['pi'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
                <div class="space-y-3">

                    <div>
                        <label class="block text-sm font-medium">İşlər tam icra, % </label>
                        <input type="text" inputmode="decimal" name="iti"
                            value="{{ old('iti', $values['iti'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">İşlər qismən icra, % </label>
                        <input type="text" inputmode="decimal" name="iqi"
                            value="{{ old('iqi', $values['iqi'] ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
            </div>



        </div>

        <div class="p-4 rounded-lg border border-gray-200 bg-white space-y-4">

            <div>
                <input type="text" name="meqfaiz"
                    value="{{ old('meqfaiz', $values['meqfaiz'] ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>






            @php
            $diagramBlock = $values['diagram_block'] ?? [];
            $bars = $diagramBlock['bars'] ?? [];
            @endphp

            <div class="rounded-xl border bg-white p-4 space-y-4">

                <div class="border rounded-lg bg-gray-50 p-3 overflow-auto">
                    <img
                        data-preview-src="{{ url('/slide-8-diagram') }}"
                        src="{{ url('/slide-8-diagram') }}?v={{ time() }}"
                        alt="Slide 8 diagram preview"
                        class="max-w-full">
                </div>

                <div class="grid grid-cols-1 gap-3">
                    @foreach ($bars as $i => $bar)
                    <div class="grid grid-cols-[1fr_90px] gap-3 items-center">
                        <input type="text"
                            name="diagram_block[bars][{{ $i }}][label]"
                            value="{{ old("diagram_block.bars.$i.label", $bar['label'] ?? '') }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm text-sm">

                        <div class="flex items-center gap-1">
                            <input type="number"
                                name="diagram_block[bars][{{ $i }}][value]"
                                value="{{ old("diagram_block.bars.$i.value", $bar['value'] ?? 0) }}"
                                min="0"
                                max="100"
                                step="1"
                                class="block w-full rounded-md border-gray-300 shadow-sm text-sm">
                            <span class="text-sm text-gray-500">%</span>
                        </div>
                    </div>
                    @endforeach
                </div>


            </div>

        </div>
    </div>

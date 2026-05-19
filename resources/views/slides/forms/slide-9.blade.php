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

    {{-- Main total --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="grid gap-2">
            <div>
                <label class="block mb-1 font-bold  text-sm">Ümumi müraciət sayı</label>
                <input
                    type="text"
                    name="total_requests"
                    value="{{ old('total_requests', $values['total_requests']) }}"
                    class="w-full border-gray-300 rounded">
            </div>

            <div>
                <label class="block mb-1 font-bold  text-sm">Tarix periodu</label>
                <input
                    type="text"
                    name="period"
                    value="{{ old('period', $values['period']) }}"
                    class="w-full border-gray-300 rounded">
            </div>


            {{-- Main indicators --}}
            <div>
                <label class="block mb-1 font-bold  text-sm">Birbaşa müraciətlər</label>
                <input
                    type="text"
                    name="number_1"
                    value="{{ old('number_1', $values['number_1']) }}"
                    class="w-full border-gray-300 rounded">
            </div>

            <div>
                <label class="block mb-1 font-bold  text-sm">Funksional yardımçı xidmətlər</label>
                <input
                    type="text"
                    name="number_2"
                    value="{{ old('number_2', $values['number_2']) }}"
                    class="w-full border-gray-300 rounded">
            </div>

            <div>
                <label class="block mb-1 font-bold  text-sm">Tibbi-Sosial Ekspert Komissiyaları</label>
                <input
                    type="text"
                    name="number_3"
                    value="{{ old('number_3', $values['number_3']) }}"
                    class="w-full border-gray-300 rounded">
            </div>

            <div>
                <label class="block mb-1 font-bold  text-sm">Dövlət Əmək Müfəttişliyi Xidməti</label>
                <input
                    type="text"
                    name="number_4"
                    value="{{ old('number_4', $values['number_4']) }}"
                    class="w-full border-gray-300 rounded">
            </div>

            <div>
                <label class="block mb-1 font-bold  text-sm">Elektron xidmətlər</label>
                <input
                    type="text"
                    name="number_5"
                    value="{{ old('number_5', $values['number_5']) }}"
                    class="w-full border-gray-300 rounded">
            </div>
        </div>

        <div class="grid p-4 rounded border border-gray-200 bg-white">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-bold  text-sm">2025-ci il müraciət sayı</label>
                    <input
                        type="text"
                        name="year_requests"
                        value="{{ old('year_requests', $values['year_requests']) }}"
                        class="w-full border-gray-300 rounded">
                </div>

                <div>
                    <label class="block mb-1 font-bold  text-sm">Kumulyativ müraciət sayı</label>
                    <input
                        type="text"
                        name="cum_requests"
                        value="{{ old('cum_requests', $values['cum_requests']) }}"
                        class="w-full border-gray-300 rounded">
                </div>
            </div>

            <div>

                {{-- Donut --}}
                @include('slides.partials.slide-9-donut', [
                'values' => $values,
                ])

            </div>
        </div>



        <div>

            {{-- Bottom indicators --}}
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block mb-1 font-bold  text-sm">Orta günlük müraciət sayı</label>
                    <input
                        type="text"
                        name="avg_dailyreqs"
                        value="{{ old('avg_dailyreqs', $values['avg_dailyreqs']) }}"
                        class="w-full border-gray-300 rounded">
                </div>

                <div>
                    <label class="block mb-1 font-bold  text-sm">Məmnunluğ — 2025-ci il</label>
                    <input
                        type="text"
                        name="sat_year"
                        value="{{ old('sat_year', $values['sat_year']) }}"
                        class="w-full border-gray-300 rounded">
                </div>

                <div>
                    <label class="block mb-1 font-bold text-sm">Məmnunluğ — Kumulyativ</label>
                    <input
                        type="text"
                        name="sat_total"
                        value="{{ old('sat_total', $values['sat_total']) }}"
                        class="w-full border-gray-300 rounded">
                </div>
            </div>

            {{-- Time indicators --}}
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block mb-1 font-bold  text-sm">Gözləmə müddəti</label>
                    <input
                        type="text"
                        name="waiting_time"
                        value="{{ old('waiting_time', $values['waiting_time']) }}"
                        class="w-full border-gray-300 rounded">
                </div>

                <div>
                    <label class="block mb-1 font-bold  text-sm">Xidmət müddəti</label>
                    <input
                        type="text"
                        name="service_time"
                        value="{{ old('service_time', $values['service_time']) }}"
                        class="w-full border-gray-300 rounded">
                </div>
            </div>

        </div>



    </div>
</div>

{{-- Direct requests --}}

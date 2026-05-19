<div class="border-neutral-200">

    <input type="hidden" name="donut_block[width]" value="{{ $donutBlock['width'] }}">
    <input type="hidden" name="donut_block[height]" value="{{ $donutBlock['height'] }}">




    <div style="display:grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap:24px;">
        @foreach ($donutBlock['charts'] as $chartIndex => $chart)

        <div class="rounded p-4 shadow-sm border border-bg-neutral-500">

            <!-- Center -->
            <div class="mb-4">
                <label class="mb-1 block text-sm font-bold"></label>
                <input
                    type="text"
                    name="donut_block[charts][{{ $chartIndex }}][center_label]"
                    value="{{ old("donut_block.charts.$chartIndex.center_label", $chart['center_label']) }}"
                    class="w-full border-gray-300 rounded">

                <label class="mb-1 block text-sm font-bold mt-2"></label>
                <input
                    type="text"
                    name="donut_block[charts][{{ $chartIndex }}][center_value]"
                    value="{{ old("donut_block.charts.$chartIndex.center_value", $chart['center_value']) }}"
                    class="w-full border-gray-300 rounded">
            </div>

            <!-- Legend -->
            <div class="space-y-2">
                @foreach ($donutBlock['legend'] as $i => $legend)

                <div class="flex items-center space-y-1">

                    <div class="text-sm font-bold truncate w-full">
                        {{ $legend['label'] }}
                    </div>

                    <div class="relative">
                        <input
                            type="number"
                            step="0.1"
                            min="0"
                            max="100"
                            inputmode="decimal"
                            name="donut_block[charts][{{ $chartIndex }}][values][{{ $i }}]"
                            value="{{ old("donut_block.charts.$chartIndex.values.$i", $chart['values'][$i] ?? 0) }}"
                            class="ui-num border-gray-300 rounded text-sm pr-6 w-20 text-left">

                        <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none">
                            %
                        </span>
                    </div>

                </div>

                @endforeach
            </div>

        </div>

        @endforeach

        <div class="mb-3 items-center">

            @foreach ($donutBlock['legend'] as $i => $legend)
            <div class="gap-y-3 flex">



                <div class="">
                    <input
                        type="color"
                        name="donut_block[legend][{{ $i }}][color]"
                        value="{{ old("donut_block.legend.$i.color", $legend['color']) }}"
                        class="h-10 w-10 border-gray-300 rounded">
                </div>

                <div class="">
                    <input
                        type="text"
                        name="donut_block[legend][{{ $i }}][label]"
                        value="{{ old("donut_block.legend.$i.label", $legend['label']) }}"
                        class="h-10 border-gray-300 rounded text-sm">
                </div>
                <div class="">
                    <input
                        type="number"
                        name="donut_block[legend][{{ $i }}][order]"
                        value="{{ old("donut_block.legend.$i.order", $legend['order'] ?? ($i + 1)) }}"
                        class="h-10 ui-num w-12 border-gray-300 rounded"
                        min="1">
                </div>

            </div>
            @endforeach
        </div>
    </div>





</div>

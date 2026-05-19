<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Slide {{ $slide->slide_number }} — {{ $activeMonth->name }}
        </h2>
    </x-slot>

    @php
    $formView = 'slides.forms.slide-' . $slide->slide_number;
    @endphp

    <div class="py-8">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="bg-neutral-100 shadow-sm sm:rounded-lg p-6">

                <form id="slideEditForm" method="POST" action="{{ route('slides.update', $slide) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3 flex justify-between items-center">
                        <span id="saveStatus" class="text-sm text-gray-500"></span>
                    </div>

                    @includeIf($formView, [
                        'slide' => $slide,
                        'values' => $values,
                    ])

                    <div class="w-full flex p-3 bg-neutral-200 rounded-md mt-5 justify-between">
                        <div>
                            <label class="block mb-1 font-bold">Status</label>
                            <select name="status" class="w-xl border-gray-300 rounded">
                                <option value="in_progress" @selected($slideStatus->status === 'in_progress')>
                                    In progress
                                </option>
                                <option value="completed" @selected($slideStatus->status === 'completed')>
                                    Completed
                                </option>
                            </select>
                        </div>

                        <div class="p-3 flex">
                            <button
                                id="saveBtn"
                                class="px-4 py-2 bg-green-500 text-white text-lg rounded flex items-center gap-2">
                                <span id="saveBtnText">Yadda saxla</span>
                            </button>

                            <a href="{{ route('dashboard') }}"
                               class="ml-3 inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                                Imtina
                            </a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('slideEditForm');
            const status = document.getElementById('saveStatus');
            const saveBtn = document.getElementById('saveBtn');
            const saveBtnText = document.getElementById('saveBtnText');

            if (!form || !saveBtn) return;

            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                saveBtn.disabled = true;
                saveBtn.classList.add('opacity-50');
                if (saveBtnText) saveBtnText.textContent = 'Saving...';
                if (status) status.textContent = 'Saving...';

                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    if (!response.ok) {
                        throw new Error('Save failed');
                    }

                    document.querySelectorAll('[data-preview-src]').forEach((img) => {
                        img.src = img.dataset.previewSrc + '?v=' + Date.now();
                    });

                    if (saveBtnText) saveBtnText.textContent = 'Saved ✔';
                    if (status) status.textContent = 'Saved';

                    setTimeout(() => {
                        saveBtn.disabled = false;
                        saveBtn.classList.remove('opacity-50');
                        if (saveBtnText) saveBtnText.textContent = 'Save';
                        if (status) status.textContent = '';
                    }, 1500);

                } catch (error) {
                    console.error(error);

                    if (saveBtnText) saveBtnText.textContent = 'Error';
                    if (status) status.textContent = 'Error while saving';

                    setTimeout(() => {
                        saveBtn.disabled = false;
                        saveBtn.classList.remove('opacity-50');
                        if (saveBtnText) saveBtnText.textContent = 'Save';
                        if (status) status.textContent = '';
                    }, 2000);
                }
            });
        });
    </script>
</x-app-layout>

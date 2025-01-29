@props([
    'personnel',
    'schedule_date',
    'rooms',
])
<button
    id="open-modal-btn-{{$schedule_date}}"
    class="text-blue-600 hover:text-blue-800 transition"
    type="button"
>
    <x-icons.work />
</button>

<div id="select-personnel-shift-{{$schedule_date}}" class="{{ ($errors->any() && session("modal_open_$schedule_date")) ? 'flex' : 'hidden' }} bg-gray-500 bg-opacity-40 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-7xl max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    افزودن شیفت کاری
                </h3>
                <button type="button" id="close-modal-btn-{{$schedule_date}}" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only close">Close modal</span>
                </button>
            </div>
            <div class="p-4 md:p-5">
                <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                    <form class="w-full " action="{{ route('schedule.store') }}" method="POST">
                        @csrf
                        <!-- انتاخب پرسنل -->
                        <input type="hidden" name="personnel_id" value="{{ $personnel }}" />
                        <x-app.input.disabled-inputs label="پرسنل انتخاب شده:" name="personnel" value="{{ App\Models\Personnel::find($personnel)->full_name }}" />

                        {{-- انتخاب روز و تاریخ --}}
                        <input type="hidden" name="schedule_date_id" value="{{ $schedule_date }}">
                        <x-app.input.disabled-inputs label="تاریخ انتخاب شده" name="schedule_date" value="{{ jdate(App\Models\Calendar::find($schedule_date)->date)->format('%A %d %B %Y') }}" />

                        {{-- عنوان شیفت --}}
                        <x-app.input.all-inputs name="title_{{ $schedule_date }}" label="عنوان شیفت:*" placeholder="عنوان شیفت را وارد کنید" />

                        {{-- انتخاب زمان  --}}
                        <div class="mx-auto flex justify-between mt-3 gap-3">
                            <div class="flex w-full flex-col items-start">
                                <label for="from_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">تایم شروع شیفت:*</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 right-0 top-0 flex items-center pr-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <input type="time" id="from_date" name="from_date_{{$schedule_date}}" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ old('from_date', '09:00') }}" required />
                                </div>
                                <x-input-error :messages="$errors->get('from_date_'.$schedule_date)" class="mt-2" />
                            </div>
                            <div class="flex w-full flex-col items-start">
                                <label for="to_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">تایم پایان شیفت:*</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 right-0 top-0 flex items-center pr-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <input type="time" id="to_date" name="to_date_{{$schedule_date}}" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ old('to_date', '20:00') }}" required />
                                </div>
                                <x-input-error :messages="$errors->get('to_date_'.$schedule_date)" class="mt-2" />
                            </div>
                        </div>

                        <!-- انتاخب خدمت -->
                        <x-app.input.all-inputs name="service_{{$schedule_date}}" type="select" label="انتخاب خدمت درمانی*" >
                            @foreach (App\Models\Personnel::find($personnel)->medicalservices as $service)
                                <option value="{{ $service->id }}" {{ old('service') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </x-app.input.all-inputs>

                        <!-- انتخاب اتاق -->
                        <x-app.input.all-inputs name="room_{{$schedule_date}}" type="select" label="انتخاب اتاق*" >
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}" {{ old('room') == $room->id ? 'selected' : '' }}>
                                    {{ $room->title }}
                                </option>
                            @endforeach
                        </x-app.input.all-inputs>

                        {{-- عملیات ایجاد و لغو --}}
                        <div class="flex justify-evenly mt-3">
                            <x-app.button.add-btn >ایجاد</x-app.add-btn>
                            <button type="button" id="cancel-modal-btn_{{$schedule_date}}" class="rounded-full  bg-red-600 dark:bg-red-800 text-white dark:text-white antialiased font-bold hover:bg-red-800 dark:hover:bg-red-900 px-4 py-2 flex items-center justify-between transition">لغو <x-cancel-icon /></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    document.addEventListener('DOMContentLoaded', function () {
        const scheduleDate = '{{ $schedule_date }}';
        const modal = document.getElementById(`select-personnel-shift-${scheduleDate}`);
        const openModal = document.getElementById(`open-modal-btn-${scheduleDate}`);
        const closeModal = document.getElementById(`close-modal-btn-${scheduleDate}`);
        const cancelModal = document.getElementById(`cancel-modal-btn-${scheduleDate}`);

        // Open Modal
        openModal.addEventListener('click', function() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        // Close Modal
        closeModal.addEventListener('click', function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        // Cancel Button
        cancelModal.addEventListener('click', function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    });
</script>

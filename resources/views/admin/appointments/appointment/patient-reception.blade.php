@extends('admin.layouts.master')

@section('content')

<div class="w-full">
    <div class=" flex flex-col justify-between gap-5">
        {{-- search box --}}
        <div class="flex justify-between">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                </div>
                <form action="{{route('appointments.appointment')}}" method="GET" >
                    @csrf
                    <input type="text" name="search_patient" value="{{ empty($chosen_patient) ? '' : $chosen_patient->national_code }}" id="patient-search" class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="جستجو بیمار براساس کدملی ...">
                </form>
            </div>

            {{-- choose patient --}}
            <div>
                <x-modal-with-toggle
                    button_title="لیست بیماران"
                    modal_title="لیست بیماران"
                    :model="$patients"
                    path="App\Models\Patient"
                >
                    {{-- patients table --}}
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                نام کامل بیمار
                            </th>
                            <th scope="col" class="px-6 py-3">
                                کد ملی بیمار
                            </th>
                            <th scope="col" class="px-6 py-3">
                                موبایل بیمار
                            </th>
                            <th scope="col" class="px-6 py-3">
                                تبعه خارجی
                            </th>
                            <th scope="col" class="px-6 py-3">
                                عملیات
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patients as $patient)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="flex items-center justify-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="ps-3">
                                        <div class="text-base font-semibold">
                                            {{ $patient->full_name }}
                                        </div>
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    @if (empty($patient->national_code))
                                        -
                                    @else
                                        {{ $patient->national_code }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $patient->mobile }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($patient->is_foreigner == false)
                                        -
                                    @else
                                        {{ $patient->passport_code }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center flex items-center justify-center">
                                    <form action="{{route('appointments.appointment')}}" method="get">
                                        @csrf
                                        <input type="hidden" name="select_patient" value="{{ $patient->id }}">
                                        <button type="submit" class="flex justify-between gap-2 font-medium ml-5 text-green-600 dark:text-blue-500 hover:underline">
                                            انتخاب بیمار
                                            <x-add-icon />
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-modal-with-toggle>
            </div>
        </div>
        @if ($chosen_patient)
            <div>
                <form action="{{ route('appointments.store') }}" method="post" class="flex flex-col justify-between gap-7">
                    @csrf
                    <div class="flex justify-between gap-7">
                        <div class="flex flex-col w-full justify-start">
                            {{-- patient's name --}}
                            <input type="hidden" name="patient_id" value="{{ $chosen_patient->id }}">
                            <x-app.input.disabled-inputs name="patient" label="نام و نام خانوادگی بیمار" :value="$chosen_patient->full_name" />

                            <!-- انتخاب خدمت -->
                            <x-app.input.all-inputs name="service_id" type="select" label="انتخاب خدمت درمانی*" initial="انتخاب خدمت درمانی">
                                @if (count(App\Models\MedicalServices::all()) == 0)
                                    <option value="0">موردی یافت نشد</option>
                                @else
                                    @foreach (App\Models\MedicalServices::all() as $service)
                                        @if ($service->display_in_list)
                                            <option value="{{$service->id}}" {{ old('service_id') ? 'selected' : ''}}>
                                                {{ $service->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </x-app.input.all-inputs>

                            <!-- انتخاب نوع نوبت -->
                            <x-app.input.all-inputs name="appointment_type" type="select" label="انتخاب نوع نوبت*" initial="انتخاب نوع نوبت">
                                <option value="normal" {{ old('appointment_type') ? 'selected' : '' }}>نوبت عادی</option>
                                <option value="emergency" {{ old('appointment_type') ? 'selected' : '' }}>نوبت اورژانسی</option>
                                <option value="vip" {{ old('appointment_type') ? 'selected' : '' }}>نوبت VIP</option>
                            </x-app.input.all-inputs>

                            <!-- انتخاب پرسنل -->
                            <x-app.input.all-inputs name="personnel_id" type="select" label="انتخاب پرسنل ارائه دهنده خدمت*" initial="جهت انتخاب پرسنل ابتدا خدمت درمانی مورد نظر را انتخاب کنید">
                            </x-app.input.all-inputs>

                            <!-- انتخاب پزشک معرف -->
                            <div class="relative">
                                <x-app.input.all-inputs name="introducer_id" type="select" label="پزشک معرف (میتواند خالی باشد)"  initial=" ">
                                    @if (count(App\Models\Personnel::all()) == 0)
                                        <option value="0">موردی یافت نشد</option>
                                    @else
                                    @foreach (App\Models\Personnel::all() as $personnel)
                                            <option value="{{ $personnel->id }}" {{ old('introducer_id') ? 'selected' : ''}}>
                                                {{ $personnel->full_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-app.input.all-inputs>
                                <button type="button" class="{{ request()->has('introducer_id') ? '' : 'hidden'}} absolute left-10 top-12">❌</button>
                            </div>

                            <!-- انتخاب پزشک معرف -->
                            <x-app.input.all-inputs name="description" type="textarea" label="توضیحات" placeholder="توضیحات مربوط به بیمار (میتواند خالی باشد)." />
                        </div>
                        <div class="flex flex-col w-full justify-start items-center">
                            <h3 class="font-bold mt-12 text-red-700" id="left-side-title">{{ request()->has('personnel_id') ? 'لطفا کمی صبر کنید ...' : 'جهت نمایش شیفت های موجود، ابتدا خدمت و پرسنل مربوطه را انتخاب کنید.' }}</h3>
                            <div class="hidden flex-col w-full justify-start" id="all-shifts">
                                <div class="flex justify-between">
                                    <h3 class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" id="table-title">شیفت مورد نظر را انتخاب کنید</h3>
                                    <a href="#" id="current-week-btn" class="font-bold">تاریخ امروز: {{ jdate(Carbon\Carbon::now())->format('%A، %d %B %Y') }}</a>
                                </div>
                                <div class="flex flex-col" id="all-shifts-table">
                                    <div class="flex justify-between items-center my-4">
                                        <!-- Previous Week Button -->
                                        <a href="#" id="previous-week-btn" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                                            هفته قبل
                                        </a>

                                        <!-- Current Week Display -->
                                        <div class="bg-blue-700 text-white px-4 py-2 rounded-lg">
                                            <span >از {{ $startOfWeek->format('%d %B') }}</span> تا <span>{{ $endOfWeek->format('%d %B') }}</span>
                                        </div>

                                        <!-- Next Week Button -->
                                        <a href="#" id="next-week-btn" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                                            هفته بعد
                                        </a>
                                    </div>
                                    <table class="table-auto w-full border-t p-4 mt-1" >
                                        <thead>
                                            <tr class="bg-gray-600">
                                                <th class="text-center py-3 border border-gray-300 font-medium text-white">روز و تاریخ</th>
                                                <th class="text-center py-3 border border-gray-300 font-medium text-white">ساعات ویزیت</th>
                                                <th class="text-center py-3 border border-gray-300 font-medium text-white">اتاق</th>
                                            </tr>
                                        </thead>
                                        <tbody id="all-shifts-details">
                                            <!-- Schedule data will be inserted here dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden justify-end gap-10" id="submit-btns">
                        <x-app.button.add-btn >ثبت شیفت</x-app.add-btn>
                        <button type="button" class="rounded-full  bg-red-600 dark:bg-red-800 text-white dark:text-white antialiased font-bold hover:bg-red-800 dark:hover:bg-red-900 px-4 py-2 flex items-center justify-between transition"><P class="ml-3">انصراف</p> <x-cancel-icon /></button>
                    </div>
                </form>
            </div>
        @else
            <h3 class="font-bold mt-3">ابتدا بیمار را انتخاب کنید</h3>
        @endif
    </div>
  </div>

@endsection



@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // get data from backend
        const services = @json(App\Models\MedicalServices::with('personnels')->get());
        const schedules = @json($schedules);
        const appointments = @json($appointments);

        // get values from inputs
        const serviceSelect = document.querySelector('select[name="service_id"]');
        const personnelSelect = document.querySelector('select[name="personnel_id"]');
        const appointmentType = document.querySelector('select[name="appointment_type"]');
        const introducer = document.querySelector('select[name="introducer_id"]');
        const description = document.querySelector('[name="description"]');

        // all shifts table
        const leftSideTitle = document.getElementById('left-side-title');
        const allShifts = document.getElementById('all-shifts');
        const allShiftsTable = document.getElementById('all-shifts-table');
        const allShiftsDetails = document.getElementById('all-shifts-details');

        // all shifts table's current, next and prev buttons
        const prevWeekBtn = document.getElementById('previous-week-btn');
        const currentWeekBtn = document.getElementById('current-week-btn');
        const nextWeekBtn = document.getElementById('next-week-btn');

        // creat new section to demonstrate devided visit times of a single shift
        const timesSection = document.createElement('div');
        const timesTable = document.createElement('table');
        const backToShiftBtn = document.createElement('button');
        const tableTitle = document.getElementById('table-title');
        const showDevidedTimes = document.createElement('div');

        // submit or cancel buttons
        const formSubmit = document.getElementById('submit-btns');

        // variable to save selectedServiceId
        let selectedServiceId;

        // Function to get query parameters from URL
        function getQueryParams() {
            const params = {};
            const queryString = window.location.search.substring(1);
            const regex = /([^&=]+)=([^&]*)/g;
            let m;
            while (m = regex.exec(queryString)) {
                params[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
            }
            return params;
        }

          // selected service
        serviceSelect.addEventListener('change', function () {
            selectedServiceId = this.value;
            const selectedService = services.find(service => service.id == selectedServiceId);

            // Clear existing options
            personnelSelect.innerHTML = '';

            // display submit btns
            formSubmit.classList.remove('flex');
            formSubmit.classList.add('hidden');

            // change shift title
            leftSideTitle.innerText = 'پرسنل مورد نظر را انتخاب کنید.';
            leftSideTitle.classList.remove('hidden');

            if (selectedService && selectedService.personnels.length > 0) {
                const defaultOption = document.createElement('option');
                defaultOption.textContent = `پرسنل های ارائه دهنده خدمت -> ${selectedService.name}`;
                defaultOption.selected = true;
                defaultOption.disabled = true;
                personnelSelect.appendChild(defaultOption);

                selectedService.personnels.forEach(personnel => {
                    const option = document.createElement('option');
                    option.value = personnel.id;
                    option.textContent = `${personnel.full_name}`;

                    personnelSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '0';
                option.textContent = 'موردی یافت نشد';
                personnelSelect.appendChild(option);

                leftSideTitle.innerText = 'برای پرسنل انتخاب شده خدمتی تعریف نشده است!';
            }

            toggleTimeDetails();

            allShifts.style.display = 'none';
            allShiftsDetails.innerHTML = '';

            // replace new selected service to URL
            const url = new URL(window.location.href);
            url.searchParams.set('service_id', selectedServiceId);
            window.history.replaceState({}, '', url);
        });

        appointmentType.addEventListener('change', function () {
            const appointmentTypeValue = this.value;

            // replace new selected appointment_type to URL
            const url = new URL(window.location.href);
            url.searchParams.set('appointment_type', appointmentTypeValue);
            window.history.replaceState({}, '', url);
        });

        personnelSelect.addEventListener('change', function () {
            const personnelId = this.value;
            const selectedService = services.find(service => service.id == selectedServiceId);
            const selectedPersonnelName = selectedService?.personnels.find(personnel => personnel.id == personnelId ? personnel : null);

            let shiftsFoundForPersonnel = false;

            allShiftsDetails.innerHTML = '';
            leftSideTitle.classList.add('hidden');

            // visiblity of submit btns
            formSubmit.classList.remove('flex');
            formSubmit.classList.add('hidden');

            // toggle shift table if user is selecting other peronnel from select box
            toggleTimeDetails();

            // replace new selected personnel to URL
            const url = new URL(window.location.href);
            url.searchParams.set('personnel_id', personnelId);
            window.history.replaceState({}, '', url);

            if (schedules.length > 0) {
                schedules.filter(schedule => {
                    if ((schedule.personnel.id == personnelId) && (schedule.service.id == selectedServiceId)) {
                        const row = document.createElement('tr');
                        const identifier = `shift-${schedule.id}`;
                        shiftsFoundForPersonnel = true;

                        // create a table row
                        row.classList.add('group', 'cursor-pointer');
                        row.setAttribute('id', identifier);
                        row.innerHTML = `
                            <td class="border p-4 text-center rounded m-2 group-hover:bg-gray-100 transition">${schedule.schedule_date}</td>
                            <td class="border p-4 text-center rounded m-2 group-hover:bg-gray-100 transition "><div class="flex flex-col justify-center items-center"><p>از ساعت: <strong>${schedule.from_date}</strong></p><p>تا ساعت: <strong>${schedule.to_date}</strong></p></div></td>
                            <td class="border p-4 text-center rounded m-2 group-hover:bg-gray-100 transition">${schedule.room}</td>
                        `;
                        allShiftsDetails.appendChild(row);
                        allShiftsTable.classList.remove('hidden');

                        // show shifts of a schedule
                        const chosenShift = document.getElementById(identifier);

                        // show times of a shift
                        chosenShift.addEventListener('click', function () {
                            // hide all-shifts table
                            allShiftsTable.classList.add('hidden');

                            // create a button to toggle betweem shifts table and times table
                            backToShiftBtn.innerHTML = `<button class="bg-blue-500 text-white px-4 py-2 rounded-lg my-4" id="toggle-${schedule.id}">شیفت ها</button>`;
                            timesSection.appendChild(backToShiftBtn);

                            // add toggle functionality
                            backToShiftBtn.addEventListener('click', function (e) {
                                e.preventDefault();
                                allShiftsTable.classList.remove('hidden');
                                timesSection.classList.add('hidden');

                                // toggle title
                                tableTitle.innerText = 'شیفت مورد نظر را انتخاب کنید.';

                                // display submit btns
                                formSubmit.classList.remove('flex');
                                formSubmit.classList.add('hidden');
                            });

                            // create shift's detail table
                            timesSection.classList.remove('hidden');
                            timesTable.classList.add('table-auto', 'w-full', 'border-t', 'p-4', 'mt-1');
                            timesSection.appendChild(timesTable);
                            const timeDetailsId = `time-details-${schedule.id}`;
                            timesTable.innerHTML = `
                                <thead>
                                    <tr class="bg-gray-600">
                                        <th class="text-center py-3 border border-gray-300 font-medium text-white">روز و تاریخ</th>
                                        <th class="text-center py-3 border border-gray-300 font-medium text-white">ساعات ویزیت</th>
                                        <th class="text-center py-3 border border-gray-300 font-medium text-white">اتاق</th>
                                    </tr>
                                </thead>
                                <tbody id="${timeDetailsId}" >
                                    <tr>
                                        <td class="border p-4 text-center rounded m-2">${schedule.schedule_date}</td>
                                        <td class="border p-4 text-center rounded m-2 "><div class="flex flex-col justify-center items-center"><p>از ساعت: <strong>${schedule.from_date}</strong></p><p>تا ساعت: <strong>${schedule.to_date}</strong></p></div></td>
                                        <td class="border p-4 text-center rounded m-2">${schedule.room}</td>
                                    </tr>
                                </tbody>
                            `;

                            allShifts.appendChild(timesSection);

                            // change table title
                            tableTitle.innerText = 'ساعت مورد نظر را انتخاب کنید.';

                        // add times
                            // devide from_date and to_date by etimated_service_time
                            services.filter((service) => {
                                service.personnels.filter(personnel => {
                                    const fromTime = schedule.from_date.split(':').map(Number);
                                    const toTime = schedule.to_date.split(':').map(Number);
                                    const fromMinutes = fromTime[0] * 60 + fromTime[1];
                                    const toMinutes = toTime[0] * 60 + toTime[1];
                                    const estimatedTime = parseInt(personnel.pivot.estimated_service_time);

                                    if ((personnel.id == personnelId) && (service.id == selectedServiceId)) {
                                        const numberOfBadges = (toMinutes - fromMinutes) / estimatedTime;

                                        // select one, deselect others
                                        let selectedBadge = null;

                                        // add showDevidedTimes to table and reset previous one
                                        showDevidedTimes.innerHTML = '';
                                        timesSection.appendChild(showDevidedTimes);
                                        showDevidedTimes.classList.add("grid", "grid-cols-3", "gap-5", "w-full", 'mt-4');

                                        // create time badges
                                        for (let i = 0; i <= numberOfBadges; i++) {
                                            const time = document.createElement('div');
                                            time.classList.add('flex', 'justify-center', 'items-center', 'group');

                                            // last time
                                            const lastTimeValue = fromMinutes + (estimatedTime * i);

                                            // convert last time value to hours and minutes
                                            function convertToTime(value) {
                                                const hours = Math.floor(value / 60);
                                                const minutes = value % 60;

                                                return `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}`;
                                            }

                                            const lastTime = convertToTime(lastTimeValue);

                                            // create badges
                                            time.innerHTML = `
                                                <span class="cursor-pointer time-badge bg-gray-100 text-gray-800 text-md font-medium inline-flex items-center py-2 px-3 flex gap-2 rounded-sm me-2 dark:bg-gray-700 dark:text-gray-400 border border-gray-500 group-hover:bg-gray-400 group-hover:text-gray-200 transition rounded-xl" id="time_${schedule.id}_${i}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>
                                                    ${lastTime}
                                                </span>
                                            `;


                                            (function (currentTime, badgeId) {
                                                const badge = time.querySelector('.time-badge');
                                                badge.addEventListener('click', function(e) {
                                                    // Deselect previous badge
                                                    if (selectedBadge && selectedBadge !== badge) {
                                                        selectedBadge.innerHTML = `
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                                </svg>
                                                                ${selectedBadge.innerText}
                                                        `;
                                                        selectedBadge.classList.remove('bg-gray-600', 'text-white');
                                                        selectedBadge.classList.add('bg-gray-100', 'text-gray-800');
                                                    }

                                                    // Toggle current badge
                                                    const isSelected = badge.classList.contains('bg-gray-100');

                                                    if (isSelected) {
                                                        badge.innerHTML = `
                                                            <input type="hidden" name="time_${schedule.id}" value="${currentTime}">
                                                            <input type="hidden" name="schedule_id" value="${schedule.id}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                            </svg>
                                                            ${currentTime}
                                                        `;
                                                        badge.classList.add('bg-gray-600', 'text-white');
                                                        badge.classList.remove('bg-gray-100', 'text-gray-800');
                                                        selectedBadge = badge;

                                                        // display submit btns
                                                        formSubmit.classList.add('flex');
                                                        formSubmit.classList.remove('hidden');
                                                    } else {
                                                        badge.innerHTML = `
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                                </svg>
                                                                ${currentTime}
                                                            `;
                                                            badge.classList.remove('bg-gray-600', 'text-white');
                                                            badge.classList.add('bg-gray-100', 'text-gray-800');
                                                            selectedBadge = null;

                                                            // display submit btns
                                                            formSubmit.classList.remove('flex');
                                                            formSubmit.classList.add('hidden');
                                                        }
                                                    });

                                                    // activate time badges if is added to appointment
                                                    appointments.filter(appointment => {
                                                        const timeFromDB = appointment.estimated_service_time.split(' ')[1];
                                                        const reservedTimeValue = `${currentTime}:00`;

                                                        if ((timeFromDB == reservedTimeValue) && (schedule.id == appointment.schedule_id)) {
                                                            badge.innerHTML = `
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                                </svg>
                                                                ${currentTime}
                                                            `;
                                                            badge.disabled = true;
                                                            badge.classList.add('bg-red-800', 'text-white', 'pointer-events-none', 'opacity-50');
                                                            badge.classList.remove('bg-gray-100', 'text-gray-800', 'group-hover:bg-gray-400', 'group-hover:text-gray-200', 'transition');
                                                        }
                                                    })
                                            })(lastTime, `time_${schedule.id}_${i}`);

                                            showDevidedTimes.appendChild(time);
                                        }
                                    }
                                });
                            });
                        });
                    }
                });

                // no shifts found
                if (! shiftsFoundForPersonnel) {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td colspan="4" class="pt-4">شیفتی برای <strong>${selectedPersonnelName?.full_name}</strong> در خدمت درمانی <strong>${selectedService?.name}</strong> ثبت نشده است.</td>`;
                    allShiftsDetails.appendChild(row);
                }
            } else {
                // message for selected personnel if there is no shifts added to personnel in other weeks
                const row = document.createElement('tr');
                row.innerHTML =  `<td colspan="4" class="pt-4">شیفتی برای <strong>${selectedPersonnelName?.full_name}</strong> در خدمت درمانی <strong>${selectedService?.name}</strong> ثبت نشده است.</td>`;
                allShiftsDetails.appendChild(row);
            }

            allShifts.style.display = 'block';
        });

        // Set initial values from query parameters
        const queryParams = getQueryParams();
        if (queryParams.service_id) {
            serviceSelect.value = queryParams.service_id;
            serviceSelect.dispatchEvent(new Event('change'));
        }
        if (queryParams.appointment_type) {
            appointmentType.value = queryParams.appointment_type;
            appointmentType.dispatchEvent(new Event('change'));
        }
        if (queryParams.personnel_id) {
            personnelSelect.value = queryParams.personnel_id;
            personnelSelect.dispatchEvent(new Event('change'));
        }
        if (queryParams.introducer_id) {
            introducer.value = queryParams.introducer_id;
            introducer.dispatchEvent(new Event('change'));
        }
        if (queryParams.description) {
            description.value = queryParams.description;
            description.dispatchEvent(new Event('change'));
        }

        // navigate through weeks
        function updateWeekNavigationLinks() {
            const selectedServiceId = serviceSelect.value;
            const selectedPersonnelId = personnelSelect.value;
            const appointmentTypeId = appointmentType.value;
            const introducerId = introducer.value;
            const descriptionValue = description.value;

            let prevWeekUrl = `{{ route('appointments.appointment', ['week' => $currentDate->copy()->subWeek()->format('Y-m-d')]) }}&select_patient={{ $chosen_patient->id ?? '' }}`;
            let nextWeekUrl = `{{ route('appointments.appointment', ['week' => $currentDate->copy()->addWeek()->format('Y-m-d')]) }}&select_patient={{ $chosen_patient->id ?? '' }}`;

            // add selected service
            if (selectedServiceId) {
                prevWeekUrl += `&service_id=${selectedServiceId}`;
                nextWeekUrl += `&service_id=${selectedServiceId}`;
            }

            // add selected appointment_type
            if (appointmentTypeId) {
                prevWeekUrl += `&appointment_type=${appointmentTypeId}`;
                nextWeekUrl += `&appointment_type=${appointmentTypeId}`;
            }

            // add selected personnel
            if (selectedPersonnelId) {
                prevWeekUrl += `&personnel_id=${selectedPersonnelId}`;
                nextWeekUrl += `&personnel_id=${selectedPersonnelId}`;
            }

            // add selected introducer
            if (introducerId) {
                prevWeekUrl += `&introducer_id=${introducerId}`;
                nextWeekUrl += `&introducer_id=${introducerId}`;
            }

            // add selected description
            if (descriptionValue) {
                prevWeekUrl += `&description=${descriptionValue}`;
                nextWeekUrl += `&description=${descriptionValue}`;
            }


              prevWeekBtn.href = prevWeekUrl;
              nextWeekBtn.href = nextWeekUrl;
          }

          // current week
          currentWeekBtn.addEventListener('click', function () {
              const selectedServiceId = serviceSelect.value;
              const selectedPersonnelId = personnelSelect.value;
              const appointmentTypeId = appointmentType.value;
              const introducerId = introducer.value;
              const descriptionValue = description.value;
              const today = new Date().toISOString().split('T')[0];

              let currentWeekUrl = `{{ route('appointments.appointment', ['week' => '' ]) }}${today}&select_patient={{ $chosen_patient->id ?? '' }}`;

              // add selected service
              if (selectedServiceId) {
                  currentWeekUrl += `&service_id=${selectedServiceId}`;
              }

              // add selected appointment_type
              if (appointmentTypeId) {
                  currentWeekUrl += `&appointment_type=${appointmentTypeId}`;
            }

              // add selected introducer
              if (introducerId) {
                  currentWeekUrl += `&introducer_id=${introducerId}`;
              }

              // add selected description
              if (descriptionValue) {
                  currentWeekUrl += `&description=${descriptionValue}`;
              }

              // add selected personnel
              if (selectedPersonnelId) {
                  currentWeekUrl += `&personnel_id=${selectedPersonnelId}`;
              }

              currentWeekBtn.href = currentWeekUrl;
          })

          serviceSelect.addEventListener('change', updateWeekNavigationLinks);
          personnelSelect.addEventListener('change', updateWeekNavigationLinks);
          appointmentType.addEventListener('change', updateWeekNavigationLinks);
          introducer.addEventListener('change', updateWeekNavigationLinks);
          description.addEventListener('change', updateWeekNavigationLinks);

          // Initial update of week navigation links
          updateWeekNavigationLinks();

          // toggle times grid
          function toggleTimeDetails() {
              allShiftsTable.classList.remove('hidden');
              timesSection.classList.add('hidden');

              // toggle title
              tableTitle.innerText = 'شیفت مورد نظر را انتخاب کنید.';
          }

        //   toggle delete button of selected introducer
        const clearButton = introducer.closest('.relative').querySelector('button');
        introducer.addEventListener('change', function () {
            clearButton.classList.remove('hidden');
        });
        clearButton.addEventListener('click', function () {
            introducer.value = '';
            this.classList.add('hidden');

            // Remove introducer_id from URL
            const url = new URL(window.location.href);
            url.searchParams.delete('introducer_id');
            window.history.replaceState({}, '', url);
        });
    });
</script>
@endsection

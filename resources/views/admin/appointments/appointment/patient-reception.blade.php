@extends('admin.layouts.master')

@section('content')

<div class="w-full">
    <div class=" flex flex-col justify-between gap-5">
        <div class="flex justify-between">
            <div class="flex justify-start items-center gap-3">
                <div class="relative flex justify-center items-center">
                    <button id="search-patient-code-btn"  class="absolute font-bold text-white bg-blue-700 hover:bg-blue-800 transition top-1.5  py-1.5 px-3 rounded-lg left-2 ">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                    </button>
                    <input type="text" id="search-patient-code" class="block p-3 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="جستجو بیمار براساس کدملی ...">
                </div>

                <!-- patient modal -->
                <div class="flex">
                    <div id="patients-table-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
                        <div class="max-w-4xl w-full bg-white p-6 rounded-lg shadow-lg">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold">انتخاب بیمار</h2>
                                <button id="close-patients-table" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    <span class="sr-only close">Close modal</span>
                                </button>
                            </div>
                            <!-- Search Box -->
                            <div class="relative w-1/2">
                                <label for="search-patient" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">جستجو بیمار</label>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 absolute right-2 bottom-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                                <input id="search-patient" type="text" placeholder="جستجو بیمار براساس نام و نام خانوادگی، کدملی یا شماره موبایل" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>

                            <div id="" class="border rounded mt-5">
                                <table class="w-full text-sm text-left rtl:text-center text-gray-800 dark:text-gray-400 rounded-lg overflow-hidden">
                                    <thead id="patients-table-head" class="text-xs text-white uppercase bg-gray-800 dark:bg-gray-200 dark:text-gray-400 rounded-t-lg">
                                        <tr class="rounded-t-lg border-b border-gray-300">
                                            <th class="p-4">نام کامل بیمار</th>
                                            <th class="p-4">کدملی/شماره پاسپورت بیمار</th>
                                            <th class="p-4">موبایل بیمار</th>
                                            <th class="p-4">تبعه خارجی</th>
                                            <th class="p-4">عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="patients-table-body">
                                        <!-- Jalali calendar will be generated here -->
                                    </tbody>
                                </table>
                            </div>
                            <div id="patients-pagination" class="flex justify-center pt-4">
                                <!-- JavaScript will populate this -->
                            </div>
                        </div>
                    </div>
                    <!-- Button to Open Modal -->
                    <button id="open-patients-modal" class="flex justify-between items-center text-white bg-gray-700 hover:bg-gray-300 hover:text-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm py-2 px-3 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800 transition cursor-pointer"><x-icons.users-group /></button>
                </div>

                <!-- create patient -->
                <a href="{{route('patient.create')}}" class="flex justify-between items-center text-white bg-green-600 hover:bg-gray-100 hover:text-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 px-3 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 transition cursor-pointer"><x-icons.add-user /></a>
            </div>

            <!-- live timer -->
            <div class="flex flex-col justify-between items-center">
                <div id="live-timer" class="text-lg font-bold">
                    <span id="hours"></span><span id="colon" class="hidden">:</span><span id="minutes"></span>
                </div>
                <hr class="w-full h-1 mx-auto my-1 bg-gray-300 border-0 rounded-sm">
                <h2 id="current-month-year" class="text-lg font-bold mb-2"></h2>
            </div>
        </div>
        <div id="display-appointment-for-selected-patient">
            <h3 id="initial-title" class="font-bold mt-3">جهت نمایش شیفت ها برای نوبت دهی، ابتدا بیمار مورد نظر را انتخاب کنید</h3>
        </div>
    </div>
  </div>

@endsection



@section('script')
<script src="https://cdn.jsdelivr.net/npm/jalaali-js/dist/jalaali.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jalaali-js/dist/jalaali.min.js"></script>
<script src="https://unpkg.com/jalaali-js/dist/jalaali.js"></script>
<script src="https://unpkg.com/jalaali-js/dist/jalaali.min.js"></script>
<script>
    // get data from server
    const appointments = @json($appointments);
    const schedules = @json($schedules);
    const patients = @json($patients);
    const personnels = @json($personnels);
    const services = @json($services);

    // validation errors
    const reservationValidationError = @json(session('reservation_validation'));

    // global variables
    const currentMonthAndYear = document.getElementById('current-month-year');

    // date variables
    let currentDate = new Date();
    let jalaliDate = jalaali.toJalaali(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());
    const todayDate = jalaali.toJalaali(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());

    // search patient by national code
    const searchPatients = document.getElementById('search-patient-code');
    const searchPatientsBtn = document.getElementById('search-patient-code-btn');
    // search patient via input
    searchPatients.addEventListener('keyup', (e) => {
        if (e.key === 'Enter') {
            const searchedPatient = patients.find(patient => (patient.national_code == searchPatients.value.trim() || patient.passport_code == searchPatients.value.trim()));

            if (searchedPatient == undefined || searchedPatient == '') {
                document.getElementById('initial-title').innerText = 'موردی یافت نشد.';
            } else {
                displaySelectedPatinet(searchedPatient);
            }
        }
    });
    // search patient using search button
    searchPatientsBtn.addEventListener('click', () => {
        const searchedPatient = patients.find(patient => (patient.national_code == searchPatients.value.trim() || patient.passport_code == searchPatients.value.trim()));

        if (searchedPatient == undefined || searchedPatient == '') {
            document.getElementById('initial-title').innerText = 'موردی یافت نشد.';
        } else {
            displaySelectedPatinet(searchedPatient);
        }
    });

    // variables of patients modal
    const patientsModal = document.getElementById('patients-table-modal');
    const openPatientsModalBtn = document.getElementById('open-patients-modal');
    const closePatientsModalBtn = document.getElementById('close-patients-table');
    const searchPatientsInModal = document.getElementById('search-patient');
    const patientsTableHead = document.getElementById('patients-table-head');
    const patientsTableBody = document.getElementById('patients-table-body');

    // open patients modal
    openPatientsModalBtn.addEventListener('click', () => {
        patientsModal.classList.remove('hidden');

        // create patients table
        generatePatientsTable(patients);
    });

    // close patients modal
    closePatientsModalBtn.addEventListener('click', () => {
        patientsModal.classList.add('hidden');
    });

    // function for creating patients table
    function generatePatientsTable(patients) {
        // number of rows to display in single page of table
        const rowsPerPage = 5;
        let currentPage = 1;

        function displayPatients(patients, page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedPatients = patients.slice(start, end);
            const searchQuery = searchPatientsInModal.value.toLowerCase();
            let rows = '';

            patientsTableBody.innerHTML = '';

            // display single row of patient
            paginatedPatients.forEach((patient, index) => {
                const matchesSearch = patient.full_name.toLowerCase().includes(searchQuery) || patient.national_code?.includes(searchQuery) || patient.passport_code?.includes(searchQuery) || patient.mobile.includes(searchQuery);
                if (matchesSearch) {
                    rows += `<tr class="border border-b border-gray-300 p-4 text-center rounded m-2 transition ${index % 2 == 0 ? 'bg-gray-100' : ''} hover:bg-gray-300">
                        <td class="p-2 font-bold ">${patient.full_name}</td>
                        <td class="p-2">${patient.is_foreigner != true ? patient.national_code : patient.passport_code}</td>
                        <td class="p-2">${patient.mobile}</td>
                        <td class="p-2 ${patient.is_foreigner && 'text-green-500 font-bold'}">${patient.is_foreigner ? 'آری' : '-'}</td>
                        <td class="p-2">
                            <button class="text-blue-500 hover:text-blue-700 text-sm font-semibold select-patient" data-id="${patient.id}">
                                <x-add-icon />
                            </button>
                        </td>
                    </tr>`;
                }
            });

            // if there is no patients exists
            if (rows === '') {
                rows = `<tr><td colspan="4" class="p-4 text-center">هیچ بیماری یافت نشد</td></tr>`;
            }

            // insert to html
            patientsTableBody.innerHTML = rows;

            // Add event listeners to the select buttons, close patient modal and add selected patient to appointment table
            document.querySelectorAll('.select-patient').forEach(button => {
                button.addEventListener('click', function () {
                    const patientId = this.getAttribute('data-id');
                    const selectedPatient = patients.find(patient => patient.id == patientId);

                    displaySelectedPatinet(selectedPatient);

                    patientsModal.classList.add('hidden');
                });
            });
        }

        // pagination setups
        function createPageButton(page, patients) {
            const button = document.createElement('button');
            button.classList.add('relative', 'inline-flex', 'items-center', 'px-4', 'py-2', 'text-sm', 'font-semibold', 'text-gray-700', 'ring-1', 'ring-gray-300', 'ring-inset');
            button.innerText = page;
            if (page === currentPage) {
                button.classList.add('bg-blue-500', 'text-white');
            }
            button.addEventListener('click', function () {
                currentPage = page;
                displayPatients(patients, currentPage);
                setupPagination(patients);
            });
            return button;
        }

        function setupPagination(patients) {
            const pagination = document.getElementById('patients-pagination');
            pagination.innerHTML = '';

            const pageCount = Math.ceil(patients.length / rowsPerPage);

            if (pageCount <= 1) {
                return;
            }

            const prevButton = document.createElement('button');
            prevButton.classList.add('relative', 'inline-flex', 'items-center', 'rounded-r-md', 'px-2', 'py-2', 'text-gray-400', 'ring-1', 'ring-gray-300', 'ring-inset', 'hover:bg-gray-50', 'focus:z-20', 'focus:outline-offset-0');
            prevButton.innerHTML = `
                <span class="sr-only">Previous</span>
                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            `;
            prevButton.addEventListener('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    displayPatients(patients, currentPage);
                    setupPagination(patients);
                }
            });
            pagination.appendChild(prevButton);

            const maxButtons = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
            let endPage = Math.min(pageCount, startPage + maxButtons - 1);

            if (startPage > 1) {
                const firstButton = createPageButton(1, patients);
                pagination.appendChild(firstButton);

                if (startPage > 2) {
                    const dots = document.createElement('span');
                    dots.innerText = '...';
                    dots.classList.add('relative', 'inline-flex', 'items-center', 'px-4', 'py-2', 'text-sm', 'font-semibold', 'text-gray-700', 'ring-1', 'ring-gray-300', 'ring-inset');
                    pagination.appendChild(dots);
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                const button = createPageButton(i, patients);
                pagination.appendChild(button);
            }

            if (endPage < pageCount) {
                if (endPage < pageCount - 1) {
                    const dots = document.createElement('span');
                    dots.innerText = '...';
                    dots.classList.add('relative', 'inline-flex', 'items-center', 'px-4', 'py-2', 'text-sm', 'font-semibold', 'text-gray-700', 'ring-1', 'ring-gray-300', 'ring-inset');
                    pagination.appendChild(dots);
                }

                const lastButton = createPageButton(pageCount, patients);
                pagination.appendChild(lastButton);
            }

            const nextButton = document.createElement('button');
            nextButton.classList.add('relative', 'inline-flex', 'items-center', 'rounded-l-md', 'px-2', 'py-2', 'text-gray-400', 'ring-1', 'ring-gray-300', 'ring-inset', 'hover:bg-gray-50', 'focus:z-20', 'focus:outline-offset-0');
            nextButton.innerHTML = `
                <span class="sr-only">Next</span>
                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                </svg>
            `;
            nextButton.addEventListener('click', function () {
                if (currentPage < pageCount) {
                    currentPage++;
                    displayPatients(patients, currentPage);
                    setupPagination(patients);
                }
            });
            pagination.appendChild(nextButton);

            if (patients.length === 0) {
                pagination.innerHTML = '';
            }
        }

        displayPatients(patients, currentPage);
        setupPagination(patients);
    }

    searchPatientsInModal.addEventListener('input', () => generatePatientsTable(patients));


// display appointment's html after patient being selected
    // variables
    const appointmentSectionForSelectedPatient = document.getElementById('display-appointment-for-selected-patient');

    // generate appointment html
    function displaySelectedPatinet(patient) {
        appointmentSectionForSelectedPatient.innerHTML = `<form action="{{ route('appointments.store') }}" method="post" class="flex flex-col justify-between gap-7">
                @csrf
                <div class="flex justify-between gap-7">
                    <div class="flex flex-col w-full justify-start">
                        {{-- patient's name --}}
                        <input type="hidden" name="patient_id" value="${patient.id}" >
                        <div class="flex justify-between gap-3">
                            <x-app.input.disabled-inputs name="patient" label="نام و نام خانوادگی بیمار" value="${patient.full_name}"/>
                            <x-app.input.disabled-inputs name="patient" label="کدملی/شماره پاسپورت بیمار" value="${patient.is_foreigner == true ? patient.passport_code : patient.national_code}" />
                            <x-app.input.disabled-inputs name="patient" label="موبایل بیمار" value="${patient.mobile}" />
                        </div>

                        <!-- انتخاب نوع نوبت -->
                        <x-app.input.all-inputs name="appointment_type" type="select" label="انتخاب نوع نوبت*" initial="انتخاب نوع نوبت">
                            <option value="normal" {{ old('appointment_type') ? 'selected' : '' }}>نوبت عادی</option>
                            <option value="emergency" {{ old('appointment_type') ? 'selected' : '' }}>نوبت اورژانسی</option>
                            <option value="vip" {{ old('appointment_type') ? 'selected' : '' }}>نوبت VIP</option>
                        </x-app.input.all-inputs>

                        <!-- خدمات -->
                        <x-app.input.all-inputs name="service_id" type="select" label="انتخاب خدمت درمانی*" initial="یکی از خدمات درمانی را انتخاب کنید">
                            ${generateMedicalServices(services)}
                        </x-app.input.all-inputs>

                        <!-- پرسنل -->
                        <x-app.input.all-inputs name="personnel_id" type="select" label="انتخاب پرسنل مربوطه*" initial="ابتدا خدمت درمانی موردنظر را انتخاب کنید."></x-app.input.all-inputs>

                        <!-- معرف -->
                        <div class="relative">
                            <x-app.input.all-inputs name="introducer_id" type="select" label="پزشک معرف" initial=" ">

                            </x-app.input.all-inputs>
                            <button type="button" class="absolute hidden left-7 bottom-1 mb-1 bg-transparent text-red-600 font-bold px-2 py-1 rounded" id="clear-introducer">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <!-- توضیحات -->
                        <x-app.input.all-inputs name="description" type="textarea" label="توضیحات" placeholder="توضیحات مربوط به بیمار (میتواند خالی باشد)." />
                    </div>
                    <div class="flex flex-col w-full justify-start items-center">
                        <h3 class="font-bold my-4 text-red-700" id="left-side-title">جهت نمایش نوبت های موجود، ابتدا خدمت درمانی و پرسنل مرتبط با آنرا انتخاب کنید.</h3>
                        <div class="hidden flex-col w-full justify-start" id="all-shifts-side">
                            <div class="flex flex-col" id="all-shifts-table">
                                <div class="flex justify-between mb-2">
                                    <div class="flex justify-center items-center gap-3">
                                        <div class="flex justify-center items-center" id="prev-week">
                                            <x-app.button.right-arrow></x-app.button.right-arrow>
                                        </div>
                                        <div id="current-week" class="flex flex-col justify-between items-center text-black font-medium">
                                            <p class="my-1" id="start-of-week">از </p>
                                            <p class="my-1" id="end-of-week">تا </p>
                                        </div>
                                        <div class="flex justify-center items-center" id="next-week">
                                            <x-app.button.left-arrow ></x-app.button.left-arrow>
                                        </div>
                                    </div>

                                    <!-- today btn -->
                                    <div class="flex gap-2">
                                        <div id="all-shifts-side-today" class="flex justify-center items-center text-white bg-green-700 mb-2 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-3  dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 transition cursor-pointer">امروز</div>
                                    </div>
                                </div>
                                <table class="table-auto w-full border-t p-4 mt-1 text-sm text-left rtl:text-center text-gray-800 dark:text-gray-400 rounded-lg overflow-hidden">
                                    <thead class="text-xs text-white uppercase bg-gray-800 dark:bg-gray-200 dark:text-gray-400 rounded-t-lg">
                                        <tr class="bg-gray-600">
                                            <th class="text-center py-3 border border-gray-300 font-medium text-white">ردیف</th>
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
            </form>`;

        // variables
        const allShiftsSection = document.getElementById('all-shifts-side');
        const shiftsSectionMainTitle = document.getElementById('left-side-title');
        const allShiftsTable = document.getElementById('all-shifts-table');
        const allShiftsDetails = document.getElementById('all-shifts-details');

    // routing between weeks
        // current values of start and end of week variables
        const { startOfWeek, endOfWeek } = getWeekRange(currentDate);
        // show current value of startOfWeek and endOfWeek
        document.getElementById('start-of-week').innerText = formatJalaliDate(startOfWeek, true);
        document.getElementById('end-of-week').innerText = formatJalaliDate(endOfWeek, false);

        // route to previous week
        document.getElementById('prev-week').addEventListener('click', function (e) {
            e.preventDefault();
            // lower value of startOfWeek 7 numbers and set to getWeekRange
            currentDate.setDate(currentDate.getDate() - 7);
            const { startOfWeek, endOfWeek } = getWeekRange(currentDate);

            // show new updated values
            document.getElementById('start-of-week').innerText = formatJalaliDate(startOfWeek, true);
            document.getElementById('end-of-week').innerText = formatJalaliDate(endOfWeek, false);

            // get current personnel and service values
            const personnelId = document.querySelector('select[name="personnel_id"]').value;
            const serviceId = document.querySelector('select[name="service_id"]').value;

            // render previous week
            renderSchedulesForSelectedWeek(personnelId, serviceId, startOfWeek, endOfWeek);
        });

        // show current week
        document.getElementById('all-shifts-side-today').addEventListener('click', function (e) {
            e.preventDefault();
            // get date now value to getWeekRange
            currentDate = new Date();
            const { startOfWeek, endOfWeek } = getWeekRange(currentDate);

            // show new updated values
            document.getElementById('start-of-week').innerText = formatJalaliDate(startOfWeek, true);
            document.getElementById('end-of-week').innerText = formatJalaliDate(endOfWeek, false);

            // get current personnel and service values
            const personnelId = document.querySelector('select[name="personnel_id"]').value;
            const serviceId = document.querySelector('select[name="service_id"]').value;

            // render previous week
            renderSchedulesForSelectedWeek(personnelId, serviceId, startOfWeek, endOfWeek);
        });

        // route to next week
        document.getElementById('next-week').addEventListener('click', function (e) {
            e.preventDefault();
            // lower value of startOfWeek 7 numbers and set to getWeekRange
            currentDate.setDate(currentDate.getDate() + 7);
            const { startOfWeek, endOfWeek } = getWeekRange(currentDate);

            // show new updated values
            document.getElementById('start-of-week').innerText = formatJalaliDate(startOfWeek, true);
            document.getElementById('end-of-week').innerText = formatJalaliDate(endOfWeek, false);

            // get current personnel and service values
            const personnelId = document.querySelector('select[name="personnel_id"]').value;
            const serviceId = document.querySelector('select[name="service_id"]').value;

            // render previous week
            renderSchedulesForSelectedWeek(personnelId, serviceId, startOfWeek, endOfWeek);
        });

        // Event listener for service selection
        document.querySelector('select[name="service_id"]').addEventListener('change', function() {
            const selectedServiceId = this.value;
            const selectedService = services.find(service => service.id == selectedServiceId);

            // Generate personnels based on selected service
            generatePersonnels(selectedService);
        });

        // generate mini calendar
        miniCalendarModal();
    }
    // create medical services select box
    function generateMedicalServices(services) {
        let options;

        services.forEach(service => {
            options += `<option value="${service.id}">${service.name}</option>`;
        });

        return options;
    }

    // create personnels select box
    function generatePersonnels(service) {
        let options = `<option disabled selected value="">یک مورد انتخاب نمایید.</option>`;;
        const allShiftsSection = document.getElementById('all-shifts-side');
        const shiftsSectionMainTitle = document.getElementById('left-side-title');
        const allShiftsDetails = document.getElementById('all-shifts-details');

        if (service.personnels.length == 0) {
            options = `<option value="">پرسنلی برای این خدمت درمانی یافت نشد.</option>`;
            // remove appoinments table if exist and change title
            allShiftsSection.classList.add('hidden');
            shiftsSectionMainTitle.innerHTML = 'پرسنلی برای این خدمت درمانی یافت نشد.';
            allShiftsDetails.innerHTML = '';
        } else {
            service.personnels.forEach(personnel => {
                options += `<option value="${personnel.id}">${personnel.full_name}</option>`;
            });
            shiftsSectionMainTitle.innerHTML = `پرسنل مورد نظر برای ${service.name} را انتخاب کنید`;
        }

        document.querySelector('select[name="personnel_id"]').innerHTML = options;
    }

    // create introducers select box
    function generateAllPersonnels(selectedPersonnelId) {
        const introducerSelect = document.querySelector('select[name="introducer_id"]');
        let options = `<option value="">بدون معرف</option>`;

        // create select box options without selected personnel
        personnels.forEach(personnel => {
            if (selectedPersonnelId != personnel.id) {
                options += `<option value="${personnel.id}">${personnel.full_name}</option>`;
            }
        });

        // add options to select box
        introducerSelect.innerHTML = options;

        // Clear introducer button
        const clearIntroducerBtn = document.getElementById('clear-introducer');
        clearIntroducerBtn.addEventListener('click', () => {
            introducerSelect.value = '';
            clearIntroducerBtn.classList.add('hidden');
        });
        // Show clear button when an introducer is selected
        introducerSelect.addEventListener('change', () => {
            if (introducerSelect.value) {
                clearIntroducerBtn.classList.remove('hidden');
            } else {
                clearIntroducerBtn.classList.add('hidden');
            }
        });

        // Show clear button if an introducer is already selected
        if (introducerSelect.value) {
            clearIntroducerBtn.classList.remove('hidden');
        }
    }



// generate appoinment section
    // Get personnel_id value
    document.addEventListener('change', function (event) {
        // variables
        const allShiftsSection = document.getElementById('all-shifts-side');
        const shiftsSectionMainTitle = document.getElementById('left-side-title');
        const allShiftsTable = document.getElementById('all-shifts-table');
        const allShiftsDetails = document.getElementById('all-shifts-details');

        // handle changes of changing value of service select box
        if (event.target && event.target.name == 'service_id') {
            // empty previous shifts if exists
            allShiftsDetails.innerHTML = `<tr class="bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-b-lg">
                    <td colspan="8" class="px-6 py-4">
                        جهت نمایش لیست نوبتها، پرسنل مورد نظر را انتخاب نمایید.
                    </td>
                </tr>`;

            // hide these items if service_id is being changed
            document.querySelectorAll('[id^="single-shift-"]').forEach(item => item.classList.add('hidden'));
            document.getElementById('submit-btns').classList.add('hidden');
        }

        if (event.target && event.target.name === 'personnel_id') {
            // hide these items if service_id is being changed
            document.querySelectorAll('[id^="single-shift-"]').forEach(item => item.classList.add('hidden'));
            document.getElementById('submit-btns').classList.add('hidden');

            const selectedServiceId = document.querySelector('select[name="service_id"]').value;
            const personnelId = event.target.value;
            const { startOfWeek, endOfWeek } = getWeekRange(currentDate);

            // remove all shifts side hidden attribute
            allShiftsSection.classList.remove('hidden');
            // remove title
            shiftsSectionMainTitle.innerHTML = 'نوبت مورد نظر را انتخاب کنید.';

            // render schedules and time reservations for selected week
            renderSchedulesForSelectedWeek(personnelId, selectedServiceId, startOfWeek, endOfWeek);

            // remove selected personnel from introducers
            const selectedPersonnelId = document.querySelector('select[name="personnel_id"]').value;
            generateAllPersonnels(selectedPersonnelId);
        }
    });

    // valdation errors section
    if (reservationValidationError) {
        // display previously selected patient
        const selectedPatient = patients.find(patient => patient.id == reservationValidationError['patient']);

        if (selectedPatient == undefined) {
            document.getElementById('initial-title').innerText = 'موردی یافت نشد.';
        } else {
            displaySelectedPatinet(selectedPatient);
        }

        const oldValues = @json(old());

        // set old values
        if (oldValues) {
            const appointmentType = 'appointment_type';
            const description = 'description';

            if (oldValues[appointmentType]) {
                document.querySelector(`[name="${appointmentType}"]`).value = oldValues[appointmentType];
            }
            if (oldValues[description]) {
                document.querySelector(`[name="${description}"]`).value = oldValues[description];
            }
        }
    }

    // helper functions
    function renderSchedulesForSelectedWeek(personnelId, serviceId, startOfWeek, endOfWeek) {
        // variables
        const allShiftsSection = document.getElementById('all-shifts-side');
        const shiftsSectionMainTitle = document.getElementById('left-side-title');
        const allShiftsTable = document.getElementById('all-shifts-table');
        const allShiftsDetails = document.getElementById('all-shifts-details');

        // clear current schedules
        allShiftsDetails.innerHTML = '';

        // Filter schedules within the current week and selected personnel and service
        const filteredSchedules = schedules.filter((schedule) => {
            const scheduleDate = new Date(schedule.calendar.date);
            return schedule.personnel.id == personnelId && schedule.service.id == serviceId &&
                scheduleDate.getTime() >= startOfWeek.getTime() && scheduleDate.getTime() <= endOfWeek.getTime();
        });

        if (filteredSchedules.length > 0) {
            // show filtered schedules
            filteredSchedules.forEach((schedule, index) => {
                const row = document.createElement('tr');
                const identifier = `shift-${schedule.id}`;
                const gregorianDate = new Date(schedule.calendar.date);
                const jalaliDate = jalaali.toJalaali(gregorianDate.getFullYear(), gregorianDate.getMonth() + 1, gregorianDate.getDate());
                const fromDateTimeValue = `${schedule.from_date.split(' ')[1].split(':')[0]}:${schedule.from_date.split(' ')[1].split(':')[1]}`;
                const toDateTimeValue = `${schedule.to_date.split(' ')[1].split(':')[0]}:${schedule.to_date.split(' ')[1].split(':')[1]}`;

                // create a table row
                row.classList.add('group', 'cursor-pointer');
                row.setAttribute('id', identifier);
                row.innerHTML = `
                    <td class="border border-gray-300 p-4 text-center rounded m-2 group-hover:bg-gray-100 transition">${index + 1}</td>
                    <td class="border border-gray-300 p-4 text-center rounded m-2 group-hover:bg-gray-100 transition">${getPersianDaysOfWeak(gregorianDate.getDay())} ${jalaliDate.jd} ${getPersianMonthsOfYear(jalaliDate.jm)}</td>
                    <td class="border border-gray-300 p-4 text-center rounded m-2 group-hover:bg-gray-100 transition"><div class="flex flex-col justify-center items-center"><p>از ساعت: <strong>${fromDateTimeValue}</strong></p><p>تا ساعت: <strong>${toDateTimeValue}</strong></p></div></td>
                    <td class="border border-gray-300 p-4 text-center rounded m-2 group-hover:bg-gray-100 transition">${schedule.room.title}</td>
                `;

                // add to table
                allShiftsDetails.appendChild(row);
                allShiftsTable.classList.remove('hidden');

                // show every shifts time details
                const selectedShift = document.getElementById(identifier);
                selectedShift.addEventListener('click', function () {
                    // create new section to demonstrate divided visit times of a single shift
                    const reservationSection = document.createElement('div');
                    const reservationTable = document.createElement('table');
                    const backToAllShiftBtn = document.createElement('button');
                    const showDividedTimes = document.createElement('div');
                    const formSubmit = document.getElementById('submit-btns');


                    // hide all-shifts-table
                    allShiftsTable.classList.add('hidden');
                    // toggle title
                    shiftsSectionMainTitle.innerText = 'زمان مورد نظر را انتخاب کنید.';

                    // add id to reservationSection
                    reservationSection.setAttribute('id', `single-shift-${schedule.id}`);

                    // create a button to toggle between shifts table and times table
                    backToAllShiftBtn.innerHTML = `<button class="bg-blue-500 text-white px-4 py-2 rounded-lg my-4" id="toggle-${schedule.id}">شیفت ها</button>`;
                    reservationSection.appendChild(backToAllShiftBtn);

                    // add toggle functionality to back to all shifts button
                    backToAllShiftBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        allShiftsTable.classList.remove('hidden');
                        reservationSection.innerHTML = '';
                        formSubmit.classList.add('hidden');
                    });

                    // toggle reservationSection if user clicks on cancel button
                    formSubmit.lastElementChild.addEventListener('click', function () {
                        allShiftsTable.classList.remove('hidden');
                        reservationSection.classList.add('hidden');
                        formSubmit.classList.add('hidden');
                    });

                    reservationSection.classList.remove('hidden');
                    reservationTable.classList.add('table-auto', 'w-full', 'border-t', 'p-4', 'mt-1', 'text-sm', 'text-left', 'rtl:text-center', 'text-gray-800', 'dark:text-gray-400', 'rounded-lg', 'overflow-hidden');
                    reservationSection.appendChild(reservationTable);
                    const timeDetailsId = `time-details-${schedule.id}`;

                    reservationTable.innerHTML = `
                        <thead>
                            <tr class="bg-gray-600">
                                <th class="text-center py-3 border border-gray-300 font-medium text-white">روز و تاریخ</th>
                                <th class="text-center py-3 border border-gray-300 font-medium text-white">ساعات ویزیت</th>
                                <th class="text-center py-3 border border-gray-300 font-medium text-white">اتاق</th>
                            </tr>
                        </thead>
                        <tbody id="${timeDetailsId}">
                            <tr>
                                <td class="border border-gray-300 p-4 text-center rounded m-2 group-hover:bg-gray-100 transition">${getPersianDaysOfWeak(gregorianDate.getDay())} ${jalaliDate.jd} ${getPersianMonthsOfYear(jalaliDate.jm)}</td>
                                <td class="border border-gray-300 p-4 text-center rounded m-2 group-hover:bg-gray-100 transition"><div class="flex flex-col justify-center items-center"><p>از ساعت: <strong>${fromDateTimeValue}</strong></p><p>تا ساعت: <strong>${toDateTimeValue}</strong></p></div></td>
                                <td class="border border-gray-300 p-4 text-center rounded m-2 group-hover:bg-gray-100 transition">${schedule.room.title}</td>
                            </tr>
                        </tbody>
                    `;

                    allShiftsSection.appendChild(reservationSection);

                    services.filter((service) => {
                        service.personnels.filter(personnel => {
                            const fromTime = schedule.from_date.split(' ')[1].split(':').map(Number);
                            const toTime = schedule.to_date.split(' ')[1].split(':').map(Number);
                            const fromMinutes = fromTime[0] * 60 + fromTime[1];
                            const toMinutes = toTime[0] * 60 + toTime[1];
                            const estimatedTime = parseInt(personnel.pivot.estimated_service_time);

                            if ((personnel.id == personnelId) && (service.id == serviceId)) {
                                const numberOfBadges = (toMinutes - fromMinutes) / estimatedTime;

                                // select one, deselect others
                                let selectedBadge = null;

                                // add showDividedTimes to table and reset previous one
                                showDividedTimes.innerHTML = '';
                                reservationSection.appendChild(showDividedTimes);
                                showDividedTimes.classList.add('grid', 'grid-cols-3', 'gap-5', 'w-full', 'mt-4');

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

                                                // Remove hidden inputs for the previously selected time
                                                if (document.querySelector(`input[name="time_${schedule.id}"]`) &&
                                                document.querySelector(`input[name="schedule_id"]`)) {
                                                    document.querySelector(`input[name="time_${schedule.id}"]`).remove();
                                                    document.querySelector(`input[name="schedule_id"]`).remove();
                                                }
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
                                        });
                                    })(lastTime, `time_${schedule.id}_${i}`);

                                    showDividedTimes.appendChild(time);
                                }
                            }
                        });
                    });
                });
            });
        } else {
            // if all shifts table contains hidden remove it to display shifts table for filtered personnel and service
            if (allShiftsTable.classList.contains('hidden')) {
                allShiftsTable.classList.remove('hidden');
            }

            allShiftsDetails.innerHTML = `<tr class="bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-b-lg">
                <td colspan="8" class="px-6 py-4">
                    موردی یافت نشد.
                </td>
            </tr>`;
        }
    }
    function getWeekRange(date) {
        const day = (date.getDay() + 1) % 7;
        const startOfWeek = new Date(date);
        startOfWeek.setDate(date.getDate() - day); // Adjust to Saturday
        startOfWeek.setHours(0, 0, 0, 0); // Start of the day

        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6); // Add 6 days to reach Friday
        endOfWeek.setHours(23, 59, 59, 999); // End of the day

        return { startOfWeek, endOfWeek };
    }
    function formatJalaliDate(date, status) {
        const jalaliDate = jalaali.toJalaali(date.getFullYear(), date.getMonth() + 1, date.getDate());
        return `${status ? 'از' : 'تا'} ${jalaliDate.jd}/${getPersianMonthsOfYear(jalaliDate.jm)}/${jalaliDate.jy}`;
    }

    // mini calendar
    function miniCalendarModal () {
        document.getElementById('all-shifts-side-today').insertAdjacentHTML('afterend', `<div class="flex">
            <div id="mini-calendar-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
                <div class="max-w-4xl w-full bg-white p-6 rounded-lg shadow-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">انتخاب تاریخ</h2>
                        <button id="close-mini-calendar" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" type="button">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only close">Close modal</span>
                        </button>
                    </div>
                    <!-- Search Box -->
                    <input id="search-date" type="text" placeholder="تاریخ مورد نظر را وارد نمایید. (برای مثال: 1-1-1404)" class="w-full p-2 border rounded mb-4 text-right">

                    <div class="flex justify-between mb-2">
                        <div class="flex justify-center items-center gap-3">
                            <div class="" id="prev-month-mini">
                                <x-app.button.right-arrow></x-app.button.right-arrow>
                            </div>
                            <div id="current-month-mini" class="flex justify-between items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition cursor-pointer"></div>
                            <div class="" id="next-month-mini">
                                <x-app.button.left-arrow ></x-app.button.left-arrow>
                            </div>
                        </div>
                        <div id="today-minicalendar" class="flex justify-between items-center gap-2 text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-3  mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 transition cursor-pointer">امروز</div>
                    </div>
                    <!-- Mini Jalali Calendar -->
                    <div id="mini-calendar-body" class="border rounded p-2">
                        <table class="w-full text-center">
                            <thead id="mini-calendar-head">
                                <tr>
                                    <th class="p-2">شنیه</th>
                                    <th class="p-2">یکشنبه</th>
                                    <th class="p-2">دوشنبه</th>
                                    <th class="p-2">سه‌شنبه</th>
                                    <th class="p-2">چهارشنبه</th>
                                    <th class="p-2">پنج‌شنبه</th>
                                    <th class="p-2">جمعه</th>
                                </tr>
                            </thead>
                            <tbody id="mini-calendar-table-body">
                                <!-- Jalali calendar will be generated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Button to Open Modal -->
            <button id="open-mini-calendar" class="flex justify-between items-center gap-2 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-3  mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800 transition cursor-pointer"><x-icons.calendar /></button>
        </div>`);

        const miniCalendarModal = document.getElementById('mini-calendar-modal');
        const openMiniCalendarModalBtn = document.getElementById('open-mini-calendar');
        const closeMiniCalendarModalBtn = document.getElementById('close-mini-calendar');
        const searchInput = document.getElementById('search-date');
        const miniCalendarElement = document.getElementById('mini-calendar-table-body');
        const miniCalendarHead = document.getElementById('mini-calendar-head');
        const todayMini = document.getElementById('today-minicalendar');

        function generateMiniCalendar(jalaliYear, jalaliMonth) {
            const gregorianFirstDay = jalaali.toGregorian(jalaliYear, jalaliMonth, 1);
            const dateObj = new Date(gregorianFirstDay.gy, gregorianFirstDay.gm - 1, gregorianFirstDay.gd);
            const firstDayOfWeekGregorian = dateObj.getDay();
            const firstDayOfMonth = (firstDayOfWeekGregorian + 1) % 7;

            // Calculate previous month's days
            let prevYear = jalaliYear;
            let prevMonth = jalaliMonth - 1;
            if (prevMonth === 0) {
                prevMonth = 12;
                prevYear -= 1;
            }
            const prevMonthDays = jalaali.jalaaliMonthLength(prevYear, prevMonth);

            const totalDays = jalaali.jalaaliMonthLength(jalaliYear, jalaliMonth);
            let dateCounter = 1;
            let rows = '';

            // display calednar
            for (let i = 0; i < 6; i++) {
                let row = '<tr>';
                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < firstDayOfMonth) {
                        const day = prevMonthDays - (firstDayOfMonth - j - 1);
                        row += `<td class="border p-4 text-center rounded m-2 bg-white opacity-20 border-gray-300">${day}</td>`;
                    } else if (dateCounter <= totalDays) {
                        const gregorianDate = jalaali.toGregorian(jalaliYear, jalaliMonth, dateCounter);
                        const dateString = `${gregorianDate.gy}-${String(gregorianDate.gm).padStart(2, '0')}-${String(gregorianDate.gd).padStart(2, '0')}`;
                        row += `<td class="border p-4 text-center rounded transition ${j == 6 ? 'bg-gray-200' : 'bg-gray-50'} border-gray-300 hover:bg-gray-300 cursor-pointer" data-date="${dateString}">
                            ${dateCounter}
                        </td>`;
                        dateCounter++;
                    } else {
                        let nextMonthDay = dateCounter - totalDays;
                        row += `<td class="border text-center rounded bg-white opacity-20 border-gray-300">${nextMonthDay}</td>`;
                        dateCounter++;
                    }
                }
                row += '</tr>';
                rows += row;
                if (dateCounter > totalDays) break;
            }

            miniCalendarElement.innerHTML = rows;

            document.getElementById('current-month-mini').innerText = `${getPersianMonthsOfYear(jalaliMonth)} ${jalaliYear}`;
        }

        // open mini calendar modal
        openMiniCalendarModalBtn.addEventListener('click', (e) => {
            e.preventDefault();
            miniCalendarModal.classList.remove('hidden');
            generateMiniCalendar(jalaliDate.jy, jalaliDate.jm);
        });

        // close mini calendar modal
        closeMiniCalendarModalBtn.addEventListener('click', (e) => {
            e.preventDefault();
            miniCalendarModal.classList.add('hidden');
        });

        // searching a specific date method
        searchInput.addEventListener('change', (event) => {
            const selectedDate = event.target.value.split('-');
            if (selectedDate.length === 3) {
                const gregorianDate = jalaali.toGregorian(parseInt(selectedDate[0]), parseInt(selectedDate[1]), parseInt(selectedDate[2]));
                currentDate = new Date(gregorianDate.gy, gregorianDate.gm - 1, gregorianDate.gd);
                jalaliDate = jalaali.toJalaali(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());
                miniCalendarModal.classList.add('hidden');

                // Update startOfWeek and endOfWeek to contain the selected date
                const { startOfWeek, endOfWeek } = getWeekRange(currentDate);
                document.getElementById('start-of-week').innerText = formatJalaliDate(startOfWeek, true);
                document.getElementById('end-of-week').innerText = formatJalaliDate(endOfWeek, false);

                // get current personnel and service values
                const personnelId = document.querySelector('select[name="personnel_id"]').value;
                const serviceId = document.querySelector('select[name="service_id"]').value;

                // render schedules for the selected week
                renderSchedulesForSelectedWeek(personnelId, serviceId, startOfWeek, endOfWeek);
            }
        });

        // button to toggle between monthes of year, years of decade
        document.getElementById('current-month-mini').addEventListener('click', function (e) {
            e.preventDefault();

            const miniCalendarElement = document.getElementById('mini-calendar-table-body');
            const isYearView = this.getAttribute('data-view') === 'year';
            const isDecadeView = this.getAttribute('data-view') === 'decade';

            // lower columns to 4
            miniCalendarHead.innerHTML = `<tr>
                    <th colspan="8" class="p-2">ماه های ${jalaliDate.jy}</th>
                </tr>`;

            if (isDecadeView) {
                // Switch to year view
                this.setAttribute('data-view', 'year');
                this.innerText = `${jalaliDate.jy}`;
                miniCalendarElement.innerHTML = generateYearView(jalaliDate.jy);
            } else if (isYearView) {
                // Switch to decade view
                this.setAttribute('data-view', 'decade');
                this.innerText = `${jalaliDate.jy - 6} - ${jalaliDate.jy + 5}`;
                miniCalendarElement.innerHTML = generateDecadeView(jalaliDate.jy);

                miniCalendarHead.innerHTML = `<tr>
                    <th colspan="8" class="p-2">دهه ${jalaliDate.jy - 6} الی ${jalaliDate.jy + 5}</th>
                </tr>`;
            } else {
                // Switch to month view
                this.setAttribute('data-view', 'year');
                this.innerText = `${jalaliDate.jy}`;
                miniCalendarElement.innerHTML = generateYearView(jalaliDate.jy);
            }
        });

        // route to previouse month without refreshing through months in mini calendar
        document.getElementById('prev-month-mini').addEventListener('click', function (e) {
            e.preventDefault();

            // route to previous years and show monthes of previous years
            const currentView = document.getElementById('current-month-mini').getAttribute('data-view');
            if (currentView === 'year') {
                jalaliDate.jy--;
                if (jalaliDate.jy < jalaali.toJalaali(new Date()).jy - 20) {
                    jalaliDate.jy = jalaali.toJalaali(new Date()).jy - 20;
                }
                miniCalendarElement.innerHTML = generateYearView(jalaliDate.jy);
                document.getElementById('current-month-mini').innerText = `${jalaliDate.jy}`;
                miniCalendarHead.innerHTML = `<tr>
                            <th colspan="8" class="p-2">ماه های ${jalaliDate.jy}</th>
                        </tr>`;
            } else
            // route to previous decades
            if (currentView === 'decade') {
                jalaliDate.jy -= 10;
                if (jalaliDate.jy < jalaali.toJalaali(new Date()).jy - 20) {
                    jalaliDate.jy = jalaali.toJalaali(new Date()).jy - 20;
                }
                miniCalendarElement.innerHTML = generateDecadeView(jalaliDate.jy);
                document.getElementById('current-month-mini').innerText = `${jalaliDate.jy - 6} - ${jalaliDate.jy + 5}`;
                miniCalendarHead.innerHTML = `<tr>
                            <th colspan="8" class="p-2">دهه ${jalaliDate.jy - 6} الی ${jalaliDate.jy + 5}</th>
                        </tr>`;
            } else
            // route normally to previous monthes
            {
                jalaliDate.jm--;
                if (jalaliDate.jm < 1) {
                    jalaliDate.jm = 12;
                    jalaliDate.jy--;
                }
                generateMiniCalendar(jalaliDate.jy, jalaliDate.jm);
            }
        });

        // route to next month without refreshing
        document.getElementById('next-month-mini').addEventListener('click', function (e) {
            e.preventDefault();

            // route to next years and show monthes of previous years
            const currentView = document.getElementById('current-month-mini').getAttribute('data-view');
            if (currentView === 'year') {
                jalaliDate.jy++;
                if (jalaliDate.jy > jalaali.toJalaali(new Date()).jy + 20) {
                    jalaliDate.jy = jalaali.toJalaali(new Date()).jy + 20;
                }
                miniCalendarElement.innerHTML = generateYearView(jalaliDate.jy);
                document.getElementById('current-month-mini').innerText = `${jalaliDate.jy}`;
                miniCalendarHead.innerHTML = `<tr>
                            <th colspan="8" class="p-2">ماه های ${jalaliDate.jy}</th>
                        </tr>`;
            } else // route to previous decades
            if (currentView === 'decade') {
                jalaliDate.jy += 10;
                if (jalaliDate.jy > jalaali.toJalaali(new Date()).jy + 20) {
                    jalaliDate.jy = jalaali.toJalaali(new Date()).jy + 20;
                }
                miniCalendarElement.innerHTML = generateDecadeView(jalaliDate.jy);
                document.getElementById('current-month-mini').innerText = `${jalaliDate.jy - 6} - ${jalaliDate.jy + 5}`;
                miniCalendarHead.innerHTML = `<tr>
                            <th colspan="8" class="p-2">دهه ${jalaliDate.jy - 6} الی ${jalaliDate.jy + 5}</th>
                        </tr>`;
            } else
            // route normally to next monthes
            {
                jalaliDate.jm++;
                if (jalaliDate.jm > 12) {
                    jalaliDate.jm = 1;
                    jalaliDate.jy++;
                }
                generateMiniCalendar(jalaliDate.jy, jalaliDate.jm);
            }
        });


        todayMini.addEventListener('click', function (e) {
            e.preventDefault();

            jalaliDate = jalaali.toJalaali(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());
            generateMiniCalendar(jalaliDate.jy, jalaliDate.jm);
            miniCalendarHead.innerHTML = `<tr>
                    <th class="p-2">شنیه</th>
                    <th class="p-2">یکشنبه</th>
                    <th class="p-2">دوشنبه</th>
                    <th class="p-2">سه‌شنبه</th>
                    <th class="p-2">چهارشنبه</th>
                    <th class="p-2">پنج‌شنبه</th>
                    <th class="p-2">جمعه</th>
                </tr>`;
            document.getElementById('current-month-mini').innerText = `${getPersianMonthsOfYear(jalaliDate.jm)} ${jalaliDate.jy}`;
        });

        // generate single year with all 12 persian monthes
        function generateYearView(jalaliYear) {
            const monthsOfYear = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
            let rows = '';
            const currentMonth = jalaali.toJalaali(new Date()).jm;

            for (let i = 0; i < 3; i++) {
                let row = '<tr>';
                for (let j = 0; j < 4; j++) {
                    const monthIndex = i * 4 + j;
                    const isCurrentMonth = (jalaliYear === jalaali.toJalaali(new Date()).jy) && (monthIndex + 1 === currentMonth);
                    row += `<td class="border p-4 text-center rounded m-2 transition ${isCurrentMonth ? 'bg-blue-200' : 'bg-gray-50'} border-gray-300 hover:bg-gray-300 cursor-pointer" data-month="${monthIndex + 1}">
                        ${monthsOfYear[monthIndex]} ${jalaliYear}
                    </td>`;
                }
                row += '</tr>';
                rows += row;
            }

            return rows;
        }

        // generate decade view containing current year + 5 previous year and 5 future years
        function generateDecadeView(currentYear) {
            let rows = '';
            const currentJalaliYear = jalaali.toJalaali(new Date()).jy;

            for (let i = 0; i < 3; i++) {
                let row = '<tr>';
                for (let j = 0; j < 4; j++) {
                    const year = currentYear - 6 + (i * 4 + j);
                    const isCurrentYear = (year === currentJalaliYear);
                    row += `<td class="border p-4 text-center rounded m-2 transition ${isCurrentYear ? 'bg-blue-200' : 'bg-gray-50'} border-gray-300 hover:bg-gray-300 cursor-pointer" data-year="${year}">
                        ${year}
                    </td>`;
                }
                row += '</tr>';
                rows += row;
            }

            return rows;
        }

        function selectMonth(jalaliYear, jalaliMonth) {
            const currentMonthMini = document.getElementById('current-month-mini');
            currentMonthMini.setAttribute('data-view', 'month');
            currentMonthMini.innerText = `${getPersianMonthsOfYear(jalaliMonth)} ${jalaliYear}`;
            jalaliDate.jy = jalaliYear; // Update jalaliDate with the selected year
            jalaliDate.jm = jalaliMonth; // Update jalaliDate with the selected month
            generateMiniCalendar(jalaliYear, jalaliMonth);
            miniCalendarHead.innerHTML = `<tr>
                    <th class="p-2">شنیه</th>
                    <th class="p-2">یکشنبه</th>
                    <th class="p-2">دوشنبه</th>
                    <th class="p-2">سه‌شنبه</th>
                    <th class="p-2">چهارشنبه</th>
                    <th class="p-2">پنج‌شنبه</th>
                    <th class="p-2">جمعه</th>
                </tr>`;
        }

        function selectYear(year) {
            const currentMonthMini = document.getElementById('current-month-mini');
            currentMonthMini.setAttribute('data-view', 'year');
            currentMonthMini.innerText = `${year}`;
            jalaliDate.jy = year; // Update jalaliDate with the selected year
            miniCalendarElement.innerHTML = generateYearView(year);
            miniCalendarHead.innerHTML = `<tr>
                    <th colspan="8" class="p-2">ماه های ${year}</th>
                </tr>`;
        }

        // route to specific date which user is selecting
        document.addEventListener('click', function (event) {
            // show selected month in year view
            if (event.target.matches('[data-month]')) {
                event.preventDefault();
                const jalaliYear = jalaliDate.jy;
                const jalaliMonth = parseInt(event.target.getAttribute('data-month'));
                selectMonth(jalaliYear, jalaliMonth);
            } else
            // show selected year from decade view
            if (event.target.matches('[data-year]')) {
                event.preventDefault();
                const year = parseInt(event.target.getAttribute('data-year'));
                selectYear(year);
            } else
            // show selected date
            if (event.target.matches('[data-date]')) {
                event.preventDefault();
                const selectedDate = event.target.getAttribute('data-date').split('-');
                const gregorianDate = new Date(selectedDate[0], selectedDate[1] - 1, selectedDate[2]);
                jalaliDate = jalaali.toJalaali(gregorianDate.getFullYear(), gregorianDate.getMonth() + 1, gregorianDate.getDate());
                currentDate = new Date(gregorianDate.getFullYear(), gregorianDate.getMonth(), gregorianDate.getDate());
                miniCalendarModal.classList.add('hidden');

                // Update startOfWeek and endOfWeek to contain the selected date
                const { startOfWeek, endOfWeek } = getWeekRange(currentDate);
                document.getElementById('start-of-week').innerText = formatJalaliDate(startOfWeek, true);
                document.getElementById('end-of-week').innerText = formatJalaliDate(endOfWeek, false);

                // get current personnel and service values
                const personnelId = document.querySelector('select[name="personnel_id"]').value;
                const serviceId = document.querySelector('select[name="service_id"]').value;

                // render schedules for the selected week
                renderSchedulesForSelectedWeek(personnelId, serviceId, startOfWeek, endOfWeek);
            }
        });
    }

    // live timer
    function updateLiveTimer() {
        const tehranOffset = 3.5 * 60 * 60 * 1000; // Tehran is UTC+03:30
        const now = new Date();
        const tehranTime = new Date(now.getTime() + tehranOffset);
        const hours = String(tehranTime.getUTCHours()).padStart(2, '0');
        const minutes = String(tehranTime.getUTCMinutes()).padStart(2, '0');
        const colonVisible = now.getSeconds() % 2 === 0;

        document.getElementById('colon').classList.remove('hidden');

        document.getElementById('hours').innerText = hours;
        document.getElementById('minutes').innerText = minutes;
        document.getElementById('colon').style.visibility = colonVisible ? 'hidden' : 'visible';
    }
    // get persian days and month
    function getPersianMonthsOfYear(jalaliMonth) {
        const monthsOfYear = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
        return monthsOfYear[jalaliMonth - 1];
    }
    function getPersianDaysOfWeak(jalaliDay) {
        const daysOfWeek = [ 'یک شنبه', 'دو شنبه', 'سه شنبه', 'چهار شنبه', 'پنج شنبه', 'جمعه', 'شنبه',];
        return daysOfWeek[jalaliDay];
    }
    // Update the timer every second
    setInterval(updateLiveTimer, 1000);
    // Initial call to display the timer immediately
    updateLiveTimer();
    // set date value to live timer
    currentMonthAndYear.innerHTML = `${getPersianDaysOfWeak(new Date().getDay())} ${todayDate.jd} ${getPersianMonthsOfYear(todayDate.jm)}`;
</script>
@endsection

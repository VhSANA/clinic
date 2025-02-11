<x-app-layout>
    <div class="py-12 ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="w-full">
                        <div class="bg-primary text-white shadow rounded-lg">
                            <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4 ">
                                <div class="flex justify-center items-center ">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <input type="text" id="table-search" class="block p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="جستجو">
                                        <button type="button" class="hidden absolute left-2 top-4">❌</button>
                                        <button type="button" class="text-white absolute left-2 top-2 bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700">جستجو بیمار</button>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{route('appointments.appointment')}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">افزودن نوبت جدید</a>
                                </div>
                            </div>
                            <div class="p-0">
                                <table class="w-full text-sm text-left rtl:text-center text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                نام بیمار
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                کدملی
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                خدمت درمانی ثبت شده
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                نام پرسنل
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                زمان تقریبی نوبت
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                وضعیت
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                عملیات
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="patients-table">
                                        @if ($showList)
                                            @foreach ($appointments as $appointment)
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                    <th scope="row" class="flex items-center justify-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        <div class="ps-3">
                                                            <div class="text-base font-semibold px-6 py-4">{{ $appointment->patient->full_name }}</div>
                                                        </div>
                                                    </th>
                                                    <td class="px-6 py-4">
                                                        @if ($appointment->patient->is_foreigner)
                                                            {{ $appointment->patient->passport_code }}
                                                        @else
                                                            {{ $appointment->patient->national_code }}
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex flex-col">
                                                            <p>خدمت: <strong>{{ $appointment->schedule->service->name }}</strong></p>
                                                            <p>در اتاق: <strong>{{ $appointment->schedule->room->title }}</strong></p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        {{ $appointment->schedule->personnel->full_name }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex flex-col">
                                                            <p>ساعت: <strong>{{ jdate($appointment->estimated_service_time)->format('H:i') }}</strong></p>
                                                            <p>روز: <strong>{{ jdate($appointment->estimated_service_time)->format('%A, %d %B %Y') }}</strong></p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        {{ $appointment->appointmentStatus->status }}
                                                    </td>
                                                    <td class="px-6 py-4 text-center flex items-center justify-center">
                                                        <a href="{{route('appointments.patients.list.store', 2)}}" class="font-medium ml-5 text-yellow-600 dark:text-yellow-500 hover:underline">صدور فاکتور</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td colspan="8" class="flex justify-center items-center px-6 py-4">کد ملی یا نام بیمار راجستجو کنید <x-icons.search /></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div id="pagination" class="flex justify-center py-4">
                                    <!-- JavaScript will populate this -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const appointments = @json($appointments);
            const showTable = @json($showList)

            // value of inputs
            const search = document.getElementById('table-search');
            const patientsTable = document.getElementById('patients-table');
            const eraseBtn = search.nextElementSibling;
            const searchPatientBtn = eraseBtn.nextElementSibling;

            // Pagination variables
            const pagination = document.getElementById('pagination');
            const rowsPerPage = 3;
            let currentPage = 1;

            // patients table with pagination logic
            function displayPatients(appointments, page) {
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                const paginatedAppointments = appointments.slice(start, end);

                // Clear existing rows
                patientsTable.innerHTML = '';

                // Render paginated patients
                if (paginatedAppointments.length > 0) {
                    paginatedAppointments.forEach(appointment => {
                        // format estimated_service_time to Persian calendar
                        const visitTime = `${appointment.estimated_service_time.split(' ')[1].split(':')[0]}:${appointment.estimated_service_time.split(' ')[1].split(':')[1]}`;
                        const visitDate = convertToJalali(appointment.estimated_service_time.split(' ')[0]);
                        const persianDayOfWeek = getPersianDayOfWeek(appointment.estimated_service_time.split(' ')[0]);
                        const monthOfYear = getPersianMonthsOfYear(appointment.estimated_service_time.split(' ')[0]);

                        const row = document.createElement('tr');
                        row.classList.add('bg-white', 'border-b', 'dark:bg-gray-800', 'dark:border-gray-700');
                        row.innerHTML = `
                            <th scope="row" class="flex items-center justify-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="ps-3">
                                    <div class="text-base font-semibold px-6 py-4">${appointment.patient.full_name}</div>
                                </div>
                            </th>
                            <td class="px-6 py-4">${ appointment.patient.is_foreigner == true ? appointment.patient.passport_code : appointment.patient.national_code}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <p>خدمت: <strong>${appointment.schedule.service.name}</strong></p>
                                    <p>در اتاق: <strong>${appointment.schedule.room.title}</strong></p>
                                </div>
                            </td>
                            <td class="px-6 py-4">${appointment.schedule.personnel.full_name}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <p>ساعت: <strong>${visitTime}</strong></p>
                                    <p>روز: <strong>${persianDayOfWeek}، ${visitDate.jd} ${monthOfYear} ${visitDate.jy}</strong></p>
                                </div>
                            </td>
                            <td class="px-6 py-4">${appointment.appointment_status.status}</td>
                            <td class="px-6 py-4 text-center flex items-center justify-center">
                                <a href="{{ route('appointments.patients.list.store', '') }}/${appointment.patient.id}" class="font-medium ml-5 text-yellow-600 dark:text-yellow-500 hover:underline">صدور فاکتور</a>
                            </td>
                        `;
                        patientsTable.appendChild(row);
                        // از اینجا که یه مدال باز کنیم مونده
                    });
                } else {
                    const row = document.createElement('tr');
                    row.classList.add('bg-white', 'border-b', 'dark:bg-gray-800', 'dark:border-gray-700');
                    row.innerHTML = `
                        <td colspan="8" class="px-6 py-4 flex">بیماری با مشخصات وارد شده یافت نشد</td>
                    `;
                    patientsTable.appendChild(row);

                    // hide paginations
                    setupPagination([]);
                }
            }

            // Function to setup pagination
            function setupPagination(appointments) {
                pagination.innerHTML = '';

                const pageCount = Math.ceil(appointments.length / rowsPerPage);

                // previous button template and style
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
                        displayPatients(appointments, currentPage);
                        setupPagination(appointments);
                    }
                });
                pagination.appendChild(prevButton);

                // add ... between pagination buttons if there are too many
                const maxButtons = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
                let endPage = Math.min(pageCount, startPage + maxButtons - 1);

                if (startPage > 1) {
                    const firstButton = createPageButton(1, appointments);
                    pagination.appendChild(firstButton);

                    if (startPage > 2) {
                        const dots = document.createElement('span');
                        dots.innerText = '...';
                        dots.classList.add('relative', 'inline-flex', 'items-center', 'px-4', 'py-2', 'text-sm', 'font-semibold', 'text-gray-700', 'ring-1', 'ring-gray-300', 'ring-inset');
                        pagination.appendChild(dots);
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    const button = createPageButton(i, appointments);
                    pagination.appendChild(button);
                }

                if (endPage < pageCount) {
                    if (endPage < pageCount - 1) {
                        const dots = document.createElement('span');
                        dots.innerText = '...';
                        dots.classList.add('relative', 'inline-flex', 'items-center', 'px-4', 'py-2', 'text-sm', 'font-semibold', 'text-gray-700', 'ring-1', 'ring-gray-300', 'ring-inset');
                        pagination.appendChild(dots);
                    }

                    const lastButton = createPageButton(pageCount, appointments);
                    pagination.appendChild(lastButton);
                }

                // next page template and style
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
                        displayPatients(appointments, currentPage);
                        setupPagination(appointments);
                    }
                });
                pagination.appendChild(nextButton);

                if (appointments == '') {
                    pagination.innerHTML = '';
                }
            }

            // search patient
            search.addEventListener('change', searchPatient);

            // Erase button functionality
            eraseBtn.addEventListener('click', function () {
                search.value = '';

                if (showTable) {
                    displayPatients(appointments, currentPage);
                    setupPagination(appointments);
                } else {
                    setupPagination([]);
                    patientsTable.innerHTML = `
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td colspan="8" class="flex justify-center items-center px-6 py-4">کد ملی یا نام بیمار راجستجو کنید <x-icons.search /></td>
                        </tr>
                    `;
                }

                // display and hide buttons
                eraseBtn.classList.add('hidden');
                searchPatientBtn.classList.add('block');
                searchPatientBtn.classList.remove('hidden');
            });

            // find patient
            searchPatientBtn.addEventListener('click', searchPatient);

            // Initial display
            if (showTable) {
                displayPatients(appointments, currentPage);
                setupPagination(appointments);
            }

        // functions
            // search patient function
            function searchPatient(e) {
                const searchedValue = e.target.value.trim();
                const filteredPatients = appointments.filter(appointment => {
                    return (appointment.patient.full_name && appointment.patient.full_name.includes(searchedValue)) || (appointment.patient.national_code && appointment.patient.national_code.includes(searchedValue));
                });

                // Clear existing rows
                patientsTable.innerHTML = '';

                // display erase button and hid search button
                eraseBtn.classList.remove('hidden');
                searchPatientBtn.classList.remove('block');
                searchPatientBtn.classList.add('hidden');

                // Display filtered patients
                displayPatients(filteredPatients, currentPage);
                setupPagination(filteredPatients);

                if (searchedValue == '') {
                    // hide earase button of search
                    eraseBtn.classList.add('hidden');
                    searchPatientBtn.classList.add('block');
                    searchPatientBtn.classList.remove('hidden');

                    if (showTable) {
                        displayPatients(appointments, currentPage);
                        setupPagination(appointments);
                    } else {
                        setupPagination([]);
                        patientsTable.innerHTML = `
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td colspan="8" class="flex justify-center items-center px-6 py-4">کد ملی یا نام بیمار راجستجو کنید <x-icons.search /></td>
                            </tr>
                        `;
                    }
                }
            }
            // Function to convert Gregorian date to Jalali date
            function convertToJalali(gregorianDate) {
                const [year, month, day] = gregorianDate.split('-').map(Number);
                const jalaaliDate = jalaali.toJalaali(year, month, day);
                return jalaaliDate;
            }
            // Function to get the Persian day of the week
            function getPersianDayOfWeek(gregorianDate) {
                const date = new Date(gregorianDate);
                const daysOfWeek = ['یک شنبه', 'دو شنبه', 'سه شنبه', 'چهار شنبه', 'پنج شنبه', 'جمعه', 'شنبه'];
                return daysOfWeek[date.getDay()];
            }
            // Function to get the Persian month
            function getPersianMonthsOfYear(gregorianDate) {
                const date = new Date(gregorianDate);
                const monthsOfYear = ['دی', 'بهمن', 'اسفند', 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر'];
                return monthsOfYear[date.getMonth()];
            }

            // function to create pagination
            function createPageButton(page, appointments) {
                const button = document.createElement('button');
                button.innerText = page;
                button.classList.add('relative', 'inline-flex', 'items-center', 'px-4', 'py-2', 'text-sm', 'font-semibold', 'text-gray-900', 'ring-1', 'ring-gray-300', 'ring-inset', 'hover:bg-gray-50', 'focus:z-20', 'focus:outline-offset-0');

                if (currentPage == page) {
                    button.classList.remove('hover:bg-gray-50');
                    button.classList.add('z-10', 'bg-blue-600', 'text-white', 'focus-visible:outline-2', 'focus-visible:outline-offset-2', 'focus-visible:outline-blue-600', 'hover:bg-blue-800', 'transition');
                }

                button.addEventListener('click', function () {
                    currentPage = page;
                    displayPatients(appointments, currentPage);
                    setupPagination(appointments);
                });

                return button;
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jalaali-js/dist/jalaali.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jalaali-js/dist/jalaali.min.js"></script>
    <script src="https://unpkg.com/jalaali-js/dist/jalaali.js"></script>
    <script src="https://unpkg.com/jalaali-js/dist/jalaali.min.js"></script>
</x-app-layout>

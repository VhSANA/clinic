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
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td colspan="8" class="flex justify-center items-center px-6 py-4">لطفا کمی صبر کنید ... </td>
                                            </tr>
                                            @foreach ($appointments as $appointment)
                                            @endforeach
                                        @else
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td colspan="8" class="flex justify-center items-center px-6 py-4">کد ملی یا نام بیمار راجستجو کنید <x-icons.search /></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="pagination" class="flex justify-center pt-4">
                            <!-- JavaScript will populate this -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // data from backedn
            const appointments = @json($appointments);
            const showTable = @json($showList);
            const showPaymentAndInvoiceModal = @json(session('show_payment_and_invoice_modal'));

            // value of inputs
            const search = document.getElementById('table-search');
            const patientsTable = document.getElementById('patients-table');
            const eraseBtn = search.nextElementSibling;
            const searchPatientBtn = eraseBtn.nextElementSibling;

            // Pagination variables
            const pagination = document.getElementById('pagination');
            const rowsPerPage = 3;
            let currentPage = 1;

        // Pagination
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
                        const serviceWithPrice = appointment.schedule.personnel.medicalservices.find(service => {
                            if ((service.pivot.personnel_id == appointment.schedule.personnel.id) && (service.pivot.medical_services_id == appointment.schedule.service.id)) {
                                return service;
                            }
                        });

                        // format estimated_service_time to Persian calendar
                        const visitTime = `${appointment.estimated_service_time.split(' ')[1].split(':')[0]}:${appointment.estimated_service_time.split(' ')[1].split(':')[1]}`;
                        const visitDate = convertToJalali(appointment.estimated_service_time.split(' ')[0]);
                        const persianDayOfWeek = getPersianDayOfWeek(appointment.estimated_service_time.split(' ')[0]);
                        const monthOfYear = getPersianMonthsOfYear(appointment.estimated_service_time.split(' ')[0]);

                        // format registered visit time to Persian calendar
                        const appointmentCreatedTime = new Date(appointment.created_at);
                        const appointmentTime = `${appointmentCreatedTime.getHours()}:${appointmentCreatedTime.getMinutes()}`;
                        const appointmentDate = convertToJalali(`${appointmentCreatedTime.getFullYear()}-${appointmentCreatedTime.getMonth()}-${appointmentCreatedTime.getDate() + 1}`);
                        const appointmentDayOfWeek = getPersianDayOfWeek(`${appointmentCreatedTime.getFullYear()}-${appointmentCreatedTime.getMonth()}-${appointmentCreatedTime.getDate() + 3}`);
                        const appointmentMonthOfYear = getPersianMonthsOfYear(`${appointmentCreatedTime.getFullYear()}-${appointmentCreatedTime.getMonth() + 1}-${appointmentCreatedTime.getDate()}`);

                        // Use template literals correctly
                        const invoiceContent = (appointment?.invoice?.appointment_id == appointment.id)
                            ? invoiceDetails(appointment, appointmentDate, appointmentTime, appointmentDayOfWeek, appointmentMonthOfYear, serviceWithPrice)
                            : issuanceOfNewInvoice(appointment, appointmentDate, appointmentTime, appointmentDayOfWeek, appointmentMonthOfYear, serviceWithPrice);

                        const row = document.createElement('tr');
                        row.classList.add('bg-white', 'border-b', 'dark:bg-gray-800', 'dark:border-gray-700');
                        row.innerHTML = `
                            <th scope="row" class="flex items-center justify-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="ps-3">
                                    <div class="text-base font-semibold px-6 py-4">${appointment.patient.full_name}</div>
                                </div>
                            </th>
                            <td class="px-6 py-4">${ appointment.patient.is_foreigner == true ? appointment.patient.passport_code : appointment.patient.national_code }</td>
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
                                <button id="open-modal-btn-${appointment.id}" class="text-blue-600 hover:text-blue-800 transition" type="button">
                                    <x-icons.work />
                                </button>

                                <div id="patient-invoice-modal-${appointment.id}" class="hidden bg-gray-500 bg-opacity-40 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative p-4 w-full max-w-7xl max-h-full">
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    صدور فاکتور
                                                </h3>
                                                <button type="button" id="close-modal-btn-${appointment.id}" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                    </svg>
                                                    <span class="sr-only close">Close modal</span>
                                                </button>
                                            </div>
                                            <div class="p-4 md:p-5">
                                                <div class="max-w-5xl mx-auto relative" id="invoice-modal-details-${appointment.id}">
                                                    ${invoiceContent}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        `;
                        patientsTable.appendChild(row);

                    // rendering Modal
                        const identifier = appointment.id;
                        const modal = document.getElementById(`patient-invoice-modal-${identifier}`);
                        const openModal = document.getElementById(`open-modal-btn-${identifier}`);
                        const closeModal = document.getElementById(`close-modal-btn-${identifier}`);
                        const cancelModal = document.getElementById(`cancel-modal-btn-${identifier}`);
                        const cancelReservation = document.getElementById(`cancel-reservation-modal-${identifier}`);
                        const invoiceContentModal = document.getElementById(`invoice-modal-details-${identifier}`);

                        // open modal if show_payment_and_invoice_modal session is available
                        if (parseInt(showPaymentAndInvoiceModal) == identifier) {
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                        }

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
                            console.log('cancel modal')
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        });

                        // Cancel Reservation
                        cancelReservation.addEventListener('click', function() {
                            invoiceContentModal.innerHTML = cancelingReservation(appointment);
                            const cancaelReservationBtn = document.getElementById(`cancel-reservation-btn-${identifier}`);

                            cancaelReservationBtn.removeEventListener('click', cancelReservationHandler);
                            cancaelReservationBtn.addEventListener('click', cancelReservationHandler);
                        });

                        function cancelReservationHandler() {
                            invoiceContentModal.innerHTML = invoiceContent;
                        }
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

            // format price
            function formatPrice(price) {
                return new Intl.NumberFormat('fa-IR', {
                    style: 'decimal',
                    minimumFractionDigits: 0
                }).format(price);
            }

            // initial view to submit for invoice
            function issuanceOfNewInvoice(appointment, appointmentDate, appointmentTime, appointmentDayOfWeek, appointmentMonthOfYear, serviceWithPrice) {
                return `<form class="w-full" action="{{ route('appointments.patients.list.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="appointment_id" value="${appointment.id}" />
                    <div class="flex justify-between w-full gap-4">
                        <div class="flex flex-col w-full justify-start">
                            <div class="mt-4">
                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                    مراجعه کننده
                                </label>
                                <input type="text" disabled value="${appointment.patient.full_name}" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                            </div>

                            <div class="mt-4">
                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                    موبایل مراجعه کننده
                                </label>
                                <input type="text" disabled value="${appointment.patient.mobile}" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                            </div>

                            <div class="mt-4">
                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                    پرسنل ارائه دهنده خدمت
                                </label>
                                <input type="text" disabled value="${appointment.schedule.personnel.full_name}" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                            </div>

                            <div class="mt-4 flex flex-col items-start">
                                <div class="w-full">
                                    <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                        تخفیف
                                    </label>
                                    <input type="number" name="discount" placeholder="در صورت تخفیف، مقدار را به تومان وارد نمایید." class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                                </div>
                                @error('discount')
                                    <p class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-col w-full justify-start">
                            <div class="mt-4">
                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                    کدملی مراجعه کننده
                                </label>
                                <input type="text" disabled value="${ appointment.patient.is_foreigner == true ? appointment.patient.passport_code : appointment.patient.national_code }" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                            </div>

                            <div class="mt-4">
                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                    تاریخ و ساعت دریافت نوبت
                                </label>
                                <input type="text" disabled value="${appointmentDayOfWeek}، ${appointmentDate.jd} ${appointmentMonthOfYear} ${appointmentDate.jy} ساعت ${appointmentTime}" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                            </div>

                            <div class="mt-4">
                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                    عنوان خدمت بهمراه قیمت به تومان
                                </label>
                                <input type="text" disabled value="خدمت ${appointment.schedule.service.name} به قیمت ${formatPrice(serviceWithPrice.pivot.service_price)} تومان" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                            </div>
                            <div class="mt-12">
                                <x-app.button.add-btn >
                                    صدور فاکتور
                                </x-app.add-btn>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="absolute left-0 bottom-0 flex justify-end gap-5">
                    <form action="{{ route('appointments.patients.list.destory', '') }}/${appointment.id}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-full gap-2 bg-red-600 dark:bg-red-800 text-white dark:text-white antialiased font-bold hover:bg-red-800 dark:hover:bg-red-900 px-4 py-2 flex items-center justify-between transition">
                            حذف نوبت <x-icons.trash />
                        </button>
                    </form>
                    <button type="submit" id="cancel-reservation-modal-${appointment.id}" class="rounded-full gap-2 bg-yellow-400 dark:bg-yellow-700 text-white dark:text-white antialiased font-bold hover:bg-yellow-600 dark:hover:bg-yellow-900 px-4 py-2 flex items-center justify-between transition">
                        کنسل کردن <x-cancel-icon />
                    </button>
                    <button type="button" id="cancel-modal-btn-${appointment.id}" class="rounded-full  bg-gray-600 dark:bg-gray-800 text-white dark:text-white antialiased font-bold hover:bg-gray-800 dark:hover:bg-gray-900 px-4 py-2 flex items-center justify-between transition">
                        لغو
                    </button>
                </div>`;
            }

            // payment details modal
            function invoiceDetails(appointment, appointmentDate, appointmentTime, appointmentDayOfWeek, appointmentMonthOfYear, serviceWithPrice) {
                return `<form class="w-full" action="{{ route('appointment.invoice', '') }}/${appointment.invoice.id}" method="POST">
                    @csrf
                    <input type="hidden" name="invoice_id" value="${appointment.invoice.id}" />
                    <div class="flex justify-between w-full gap-4">
                        <div class="flex flex-col w-full justify-start">
                            <div class="mt-4">
                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                    نام مراجعه کننده
                                </label>
                                <input type="text" disabled value="${appointment.invoice.name} ${appointment.invoice.family}" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                            </div>

                            <div class="mt-4">
                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                    کدملی مراجعه کننده
                                </label>
                                <input type="text" disabled value="${ appointment.invoice.is_foreigner == true ? appointment.invoice.passport_code : appointment.invoice.national_code }" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                            </div>

                            <div class="mt-4">
                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                    موبایل مراجعه کننده
                                </label>
                                <input type="text" disabled value="${appointment.invoice.patient_mobile}" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                            </div>

                            <div class="mt-4">
                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                    پرسنل ارائه دهنده خدمت
                                </label>
                                <input type="text" disabled value="${appointment.schedule.personnel.full_name}" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                            </div>

                            <div class="mt-4">
                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                    تاریخ و ساعت دریافت نوبت
                                </label>
                                <input type="text" disabled value="${appointmentDayOfWeek}، ${appointmentDate.jd} ${appointmentMonthOfYear} ${appointmentDate.jy} ساعت ${appointmentTime}" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                            </div>
                        </div>
                        <div class="flex flex-col w-full justify-start">
                            <div class="mt-4">
                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                    عنوان خدمت بهمراه قیمت به تومان
                                </label>
                                <input type="text" disabled value="خدمت ${appointment.schedule.service.name} به قیمت ${formatPrice(serviceWithPrice.pivot.service_price)} تومان" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                            </div>

                            <div class="flex justify-between gap-2">
                                <div class="mt-4 w-full">
                                    <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                        تخفیف
                                    </label>
                                    <input type="text" disabled value="${formatPrice(appointment.invoice.discount)} تومان" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                                </div>
                                <div class="mt-4 w-full">
                                    <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                        کسری بیمه
                                    </label>
                                    <input type="text" disabled value="${formatPrice(appointment.invoice.insurance_cost)} تومان" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                    مبلغ نهایی قابل پرداخت
                                </label>
                                <input type="text" disabled value="${formatPrice(appointment.invoice.total_to_pay)} تومان" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                            </div>

                            <div class="mt-4">
                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                    وضعیت فاکتور
                                </label>
                                <input type="text" disabled value="${appointment.invoice.invoice_status.status}" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" >
                            </div>

                            <div class="mt-12">
                                <button type="submit" class="rounded-full  bg-green-600 dark:bg-green-800 text-white dark:text-white antialiased font-bold hover:bg-green-800 dark:hover:bg-green-900 px-4 py-2 flex items-center justify-between gap-3 transition">
                                    پرداخت فاکتور <x-icons.cash />
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="absolute left-0 bottom-0 flex justify-end gap-4">
                    <form action="{{ route('appointments.patients.list.store') }}/${appointment.id}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-full  bg-cyan-600 dark:bg-cyan-800 text-white dark:text-white antialiased font-bold hover:bg-cyan-800 gap-2 dark:hover:bg-cyan-900 px-4 py-2 flex items-center justify-between transition">
                         چاپ فاکتور <x-icons.print />
                        </button>
                    </form>
                    <button type="submit" id="cancel-reservation-modal-${appointment.id}" class="rounded-full bg-yellow-400 dark:bg-yellow-700 text-white dark:text-white antialiased font-bold hover:bg-yellow-600 gap-2 dark:hover:bg-yellow-900 px-4 py-2 flex items-center justify-between transition">
                        کنسل کردن <x-cancel-icon />
                    </button>
                    <button type="button" id="cancel-modal-btn-${appointment.id}" class="rounded-full  bg-gray-600 dark:bg-gray-800 text-white dark:text-white antialiased font-bold hover:bg-gray-800 dark:hover:bg-gray-900 px-4 py-2 flex items-center justify-between transition">
                        لغو
                    </button>
                </div>`;
            }

            // canceling modal
            function cancelingReservation(appointment) {
                return `<form class="w-full" route('appointments.patients.list.cancel') }}/${appointment.id}" method="POST">
                    @csrf
                    <div class="flex justify-between w-full gap-4">
                        <div class="flex flex-col w-full justify-start">
                            <div class="flex flex-col">
                                <div class="mt-4">
                                    <label class="mb-2 block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                        علت کنسل کردن نوبت رزرو شده*
                                    </label>
                                    <textarea name="cancel_description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="توضیحی مختصر در مورد علت کنسلی نوبت رزرو شده بنویسید."></textarea>
                                </div>
                                @error('cancel_description')
                                    <p class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="mt-4 flex justify-end gap-4">
                                <button type="submit" class="rounded-full  bg-green-600 dark:bg-green-800 text-white dark:text-white antialiased font-bold hover:bg-green-800 dark:hover:bg-green-900 px-4 py-2 flex items-center justify-between gap-3 transition">
                                    ارسال
                                </button>
                                <button type="button" id="cancel-reservation-btn-${appointment.id}" class="rounded-full  bg-gray-600 dark:bg-gray-800 text-white dark:text-white antialiased font-bold hover:bg-gray-800 dark:hover:bg-gray-900 px-4 py-2 flex items-center justify-between transition">
                                    لغو
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                    `;
            }

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jalaali-js/dist/jalaali.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jalaali-js/dist/jalaali.min.js"></script>
    <script src="https://unpkg.com/jalaali-js/dist/jalaali.js"></script>
    <script src="https://unpkg.com/jalaali-js/dist/jalaali.min.js"></script>
</x-app-layout>

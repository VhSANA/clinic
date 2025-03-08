<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            خوش آمدید {{ Auth::user()->user_title }} {{ Auth::user()->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="w-full">
                        <div class="bg-primary text-white shadow rounded-lg">
                            <div class="p-0">
                                <!-- THE CALENDAR -->
                                <div id="calendar" class="flex flex-col bg-white text-gray-800 overflow-x-auto">
                                    <div class="flex justify-between items-center gap-5 ml-2 mb-2">
                                        <h2 class="text-lg font-bold mb-2">جدول شیفت پرسنل</h2>
                                        <div class="flex flex-col justify-between items-center">
                                            <div id="live-timer" class="text-lg font-bold">
                                                <span id="hours"></span><span id="colon" class="hidden">:</span><span id="minutes"></span>
                                            </div>
                                            <hr class="w-full h-1 mx-auto my-1 bg-gray-300 border-0 rounded-sm">
                                            <h2 id="current-month-year" class="text-lg font-bold mb-2"></h2>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        {{-- route through weeks --}}
                                        <div class="flex justify-center items-center gap-3">
                                            <div class="" id="prev-week">
                                                <x-app.button.right-arrow>هفته قبلی</x-app.button.right-arrow>
                                            </div>
                                            <div id="current-week" class="flex justify-between items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 cursor-pointer focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 mb-2">هفته جاری</div>
                                            <div class="" id="next-month">
                                                <x-app.button.left-arrow >هفته بعدی</x-app.button.left-arrow>
                                            </div>
                                        </div>

                                        <div class="flex gap-3">
                                            {{-- mini calendar --}}
                                            <div class="flex">
                                                <div id="mini-calendar-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
                                                    <div class="max-w-4xl w-full bg-white p-6 rounded-lg shadow-lg">
                                                        <div class="flex justify-between items-center mb-4">
                                                            <h2 class="text-lg font-semibold">انتخاب تاریخ</h2>
                                                            <button id="close-mini-calendar" class="text-gray-500 hover:text-gray-700">X</button>
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
                                                            <div id="today-mini" class="flex justify-between items-center gap-2 text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-3  mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 transition cursor-pointer">امروز</div>
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
                                            </div>

                                            {{-- filter modal --}}
                                            <div class="flex">
                                                <div id="filter-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
                                                    <div class="max-w-4xl w-full bg-white p-6 rounded-lg shadow-lg">
                                                        <div class="flex justify-between items-center mb-4">
                                                            <h2 class="text-lg font-semibold">فیلتر براساس</h2>
                                                            <button id="close-filter" class="text-gray-500 hover:text-gray-700">X</button>
                                                        </div>
                                                        <div class="relative w-1/2 mb-4">
                                                            <label for="" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">جستجو پرسنل و عنوان شیفت</label>
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 absolute right-2 bottom-2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                                            </svg>
                                                            <input id="search-personnel-filter" type="text" placeholder="جستجو براساس نام پرسنل، عنوان شیفت، اتاق و ..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5">
                                                        </div>
                                                        <div class="flex justify-between gap-5">
                                                            <div class="w-full">
                                                                <label for="personnel-filter" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">فیلتر بر اساس پرسنل</label>
                                                                <select id="personnel-filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                                                    {{-- will fill by JS --}}
                                                                </select>
                                                            </div>
                                                            <div class="w-full">
                                                                <label for="service-filter" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">فیلتر بر اساس خدمت درمانی</label>
                                                                <select id="service-filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                                                    {{-- will fill by JS --}}
                                                                </select>
                                                            </div>
                                                            <div class="w-full">
                                                                <label for="room-filter" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">فیلتر بر اساس اتاق</label>
                                                                <select id="room-filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                                                    {{-- will fill by JS --}}
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="flex justify-end mt-4">
                                                            <button id="apply-filter" class="bg-blue-500 text-white px-4 py-2 rounded-lg">اعمال فیلتر</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Button to Open Modal -->
                                                <button id="open-filter" class="flex justify-between items-center gap-2 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-3  mb-2  transition cursor-pointer"><x-icons.filter /></button>
                                            </div>

                                            {{-- add personnel modal --}}
                                            <div class="flex">
                                                <div id="personnel-table-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
                                                    <div class="max-w-4xl w-full bg-white p-6 rounded-lg shadow-lg">
                                                        <div class="flex justify-between items-center mb-4">
                                                            <h2 class="text-lg font-semibold">انتخاب پرسنل</h2>
                                                            <button id="close-personnel-table" class="text-gray-500 hover:text-gray-700">X</button>
                                                        </div>
                                                        <!-- Search Box -->
                                                        <div class="flex justify-between gap-5">
                                                            <div class="relative w-full">
                                                                <label for="" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">جستجو پرسنل یا کد پرسنلی</label>
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 absolute right-2 bottom-2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                                                </svg>
                                                                <input id="search-personnel" type="text" placeholder="جستجو براساس نام پرسنل یا شماره پرسنلی" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                            </div>
                                                            <div class="w-full">
                                                                <label for="personnel-rule" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">فیلتر بر اساس مقام پرسنل</label>
                                                                <select id="personnel-rule" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                    {{-- will fill by JS --}}
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div id="" class="border rounded mt-5">
                                                            <table class="w-full text-sm text-left rtl:text-center text-gray-800 dark:text-gray-400 rounded-lg overflow-hidden">
                                                                <thead id="personnel-table-head" class="text-xs text-white uppercase bg-gray-800 dark:bg-gray-200 dark:text-gray-400 rounded-t-lg">
                                                                    <tr class="rounded-t-lg border-b border-gray-300">
                                                                        <th class="p-4">نام پرسنل</th>
                                                                        <th class="p-4">کد پرسنلی</th>
                                                                        <th class="p-4">مقام پرسنل</th>
                                                                        <th class="p-4">عملیات</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="personnel-table-body">
                                                                    <!-- Jalali calendar will be generated here -->
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div id="personnel-pagination" class="flex justify-center pt-4">
                                                            <!-- JavaScript will populate this -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Button to Open Modal -->
                                                <button id="open-personnel-modal" class="flex justify-between items-center gap-2 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-3  mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800 transition cursor-pointer"><x-icons.add-user /></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="mt-5 w-full text-sm text-left rtl:text-center text-gray-800 dark:text-gray-400 rounded-lg overflow-hidden">
                                            <thead id="schedule-table-head" class="hidden text-xs text-white uppercase bg-gray-800 dark:bg-gray-200 dark:text-gray-400 rounded-t-lg">
                                                <tr class="rounded-t-lg border-b border-gray-300">
                                                    <th scope="col" class="px-6 py-3 border-r border-gray-300">نام پرسنل</th>
                                                    <th scope="col" class="px-6 py-3 border-r border-gray-300">شنبه</th>
                                                    <th scope="col" class="px-6 py-3 border-r border-gray-300">یک شنبه</th>
                                                    <th scope="col" class="px-6 py-3 border-r border-gray-300">دو شنبه</th>
                                                    <th scope="col" class="px-6 py-3 border-r border-gray-300">سه شنبه</th>
                                                    <th scope="col" class="px-6 py-3 border-r border-gray-300">چهار شنبه</th>
                                                    <th scope="col" class="px-6 py-3 border-r border-gray-300">پنج شنبه</th>
                                                    <th scope="col" class="px-6 py-3 border-r border-gray-300">جمعه</th>
                                                </tr>
                                            </thead>
                                            <tbody id="schedule-table-body">
                                                <tr class="bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-b-lg">
                                                    <td colspan="8" class="px-6 py-4">
                                                        هیچ شیفتی ثبت نشده است. <br><br>
                                                        برای افزودن شیفت، ابتدا پرسنل موردنظر را انتخاب کنید.
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
            // get data from backend
            const calendars = @json($calendars);
            const schedules = @json($schedules);
            const personnels = @json($personnels);
            const rules = @json($rules);
            const rooms = @json($rooms);

            // validation errors modal session
            const addScheduleValidation = @json(session('add_schedule_modal'));
            const selectedPersonnelId = @json(session('selected_personnel'));
            const editScheduleValidation = @json(session('edit_schedule_modal'));

            // personnel modal
            const personnelModal = document.getElementById('personnel-table-modal');
            const openPersonnelModalBtn = document.getElementById('open-personnel-modal');
            const closePersonnelModalBtn = document.getElementById('close-personnel-table');
            const searchPersonnel = document.getElementById('search-personnel');
            const personnelTableHead = document.getElementById('personnel-table-head');
            const personnelTableBody = document.getElementById('personnel-table-body');
            const selectBox = document.getElementById('personnel-rule');

            // date
            let currentDate = new Date();
            let jalaliDate = jalaali.toJalaali(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());
            const todayDate = jalaali.toJalaali(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());

            // global variables
            let currentRenderedSchedules = [];
            let newlyAddedPersonnel = [];

            // Function to get the start and end dates of the current week
            function getWeekRange(date) {
                const startOfWeek = new Date(date);
                startOfWeek.setDate(date.getDate() - date.getDay() - 1);
                const endOfWeek = new Date(startOfWeek);
                endOfWeek.setDate(startOfWeek.getDate() + 6);
                return { startOfWeek, endOfWeek };
            }

            // function for creating persoonels table
            function generatePersonnelsTable(personnels) {
                // number of rows to display in single page of table
                const rowsPerPage = 10;
                let currentPage = 1;

                function displayPersonnel(personnels, page) {
                    const start = (page - 1) * rowsPerPage;
                    const end = start + rowsPerPage;
                    const paginatedPersonnel = personnels.slice(start, end);
                    const selectedRuleId = selectBox.value;
                    const searchQuery = searchPersonnel.value.toLowerCase();
                    let rows = '';

                    personnelTableBody.innerHTML = '';

                    paginatedPersonnel.forEach((personnel, index) => {
                        const matchesSearch = personnel.full_name.toLowerCase().includes(searchQuery) || personnel.personnel_code.toLowerCase().includes(searchQuery);
                        personnel.user.rules.forEach(rule => {
                            if ((selectedRuleId === '' || rule.id == selectedRuleId) && matchesSearch) {
                                rows += `<tr class="border border-b border-gray-300 p-4 text-center rounded m-2 transition ${index % 2 == 0 ? 'bg-gray-100' : ''} hover:bg-gray-300">
                                    <td class="p-2 font-bold ">${personnel.full_name}</td>
                                    <td class="p-2">${personnel.personnel_code}</td>
                                    <td class="p-2">${rule.persian_title}</td>
                                    <td class="p-2">
                                        <button class="text-blue-500 hover:text-blue-700 text-sm font-semibold select-personnel" data-id="${personnel.id}">
                                            <x-add-icon />
                                        </button>
                                    </td>
                                </tr>`;
                            }
                        });
                    });

                    if (rows === '') {
                        rows = `<tr><td colspan="4" class="p-4 text-center">هیچ پرسنلی یافت نشد</td></tr>`;
                    }

                    personnelTableBody.innerHTML = rows;

                    // Add event listeners to the select buttons
                    document.querySelectorAll('.select-personnel').forEach(button => {
                        button.addEventListener('click', function () {
                            const personnelId = this.getAttribute('data-id');
                            const selectedPersonnel = personnels.find(personnel => personnel.id == personnelId);

                            // prevent from reperation
                            if (!currentRenderedSchedules.some(schedule => schedule.personnel_id == personnelId)) {
                                if (scheduleTableBody.innerHTML.includes('هیچ شیفتی ثبت نشده است.')) {
                                    scheduleTableBody.innerHTML = '';
                                }
                                displaySelectedPersonnel(selectedPersonnel);
                                const { startOfWeek, endOfWeek } = getWeekRange(currentDate);
                                newlyAddedPersonnel.push({ personnel: selectedPersonnel, startOfWeek, endOfWeek });
                            } else {
                                alert('پرسنل انتخاب شده در لیست وجود دارد.');
                            }

                            personnelModal.classList.add('hidden');
                        });
                    });
                }

                function createPageButton(page, personnels) {
                    const button = document.createElement('button');
                    button.classList.add('relative', 'inline-flex', 'items-center', 'px-4', 'py-2', 'text-sm', 'font-semibold', 'text-gray-700', 'ring-1', 'ring-gray-300', 'ring-inset');
                    button.innerText = page;
                    if (page === currentPage) {
                        button.classList.add('bg-blue-500', 'text-white');
                    }
                    button.addEventListener('click', function () {
                        currentPage = page;
                        displayPersonnel(personnels, currentPage);
                        setupPagination(personnels);
                    });
                    return button;
                }

                function setupPagination(personnels) {
                    const pagination = document.getElementById('personnel-pagination');
                    pagination.innerHTML = '';

                    const pageCount = Math.ceil(personnels.length / rowsPerPage);

                    if (pageCount <= 1) {
                        return; // No need to show pagination if there's only one page or less
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
                            displayPersonnel(personnels, currentPage);
                            setupPagination(personnels);
                        }
                    });
                    pagination.appendChild(prevButton);

                    const maxButtons = 5;
                    let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
                    let endPage = Math.min(pageCount, startPage + maxButtons - 1);

                    if (startPage > 1) {
                        const firstButton = createPageButton(1, personnels);
                        pagination.appendChild(firstButton);

                        if (startPage > 2) {
                            const dots = document.createElement('span');
                            dots.innerText = '...';
                            dots.classList.add('relative', 'inline-flex', 'items-center', 'px-4', 'py-2', 'text-sm', 'font-semibold', 'text-gray-700', 'ring-1', 'ring-gray-300', 'ring-inset');
                            pagination.appendChild(dots);
                        }
                    }

                    for (let i = startPage; i <= endPage; i++) {
                        const button = createPageButton(i, personnels);
                        pagination.appendChild(button);
                    }

                    if (endPage < pageCount) {
                        if (endPage < pageCount - 1) {
                            const dots = document.createElement('span');
                            dots.innerText = '...';
                            dots.classList.add('relative', 'inline-flex', 'items-center', 'px-4', 'py-2', 'text-sm', 'font-semibold', 'text-gray-700', 'ring-1', 'ring-gray-300', 'ring-inset');
                            pagination.appendChild(dots);
                        }

                        const lastButton = createPageButton(pageCount, personnels);
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
                            displayPersonnel(personnels, currentPage);
                            setupPagination(personnels);
                        }
                    });
                    pagination.appendChild(nextButton);

                    if (personnels.length === 0) {
                        pagination.innerHTML = '';
                    }
                }

                displayPersonnel(personnels, currentPage);
                setupPagination(personnels);
            }

            // generate personnel's rule select box for filter
            function generateRulesSelectBox() {
                let options = '';

                options += `<option disabled selected value="">یکی از مقام های زیر را انتخاب کنید.</option>`;

                rules.forEach(rule => {
                    options += `<option value="${rule.id}">${rule.persian_title}</option>`;
                });

                selectBox.innerHTML = options;
            }

            openPersonnelModalBtn.addEventListener('click', () => {
                personnelModal.classList.remove('hidden');
                generatePersonnelsTable(personnels);
                generateRulesSelectBox();
            });

            closePersonnelModalBtn.addEventListener('click', () => {
                personnelModal.classList.add('hidden');
            });

            // filter out personnels based on rule and serch
            selectBox.addEventListener('change', () => generatePersonnelsTable(personnels));
            searchPersonnel.addEventListener('input', () => generatePersonnelsTable(personnels));

        // schedule
            const scheduleTableHead = document.getElementById('schedule-table-head');
            const scheduleTableBody = document.getElementById('schedule-table-body');

            let copiedSchedule = null;

            window.copySchedule = function(personnelId, date) {
                const calendarEntry = calendars.find(calendar => calendar.date.split(' ')[0] == date);
                if (calendarEntry) {
                    copiedSchedule = calendarEntry.schedules.filter(schedule => schedule.personnel_id === personnelId);
                    alert('کپی شد!');
                } else {
                    alert('هیچ شیفتی برای کپی کردن وجود ندارد.');
                }
            }

            window.pasteSchedule = function(personnelId, date) {
                if (copiedSchedule) {
                    const calendarEntry = calendars.find(calendar => calendar.date.split(' ')[0] == date);
                    if (calendarEntry) {
                        const copiedPersonnelId = copiedSchedule[0].personnel_id;
                        if (copiedPersonnelId === personnelId) {
                            copiedSchedule.forEach(schedule => {
                                const newSchedule = { ...schedule, schedule_date_id: calendarEntry.id };

                                // save to DB
                                saveSchedule(newSchedule, personnelId);
                            });
                        } else {
                            alert('عملیات غیر مجاز! عدم تطابق پرسنل کپی شده با پرسنل انتخابی');
                        }
                    } else {
                        alert('شیفت کپی شده نامعتبر میباشد.');
                    }
                } else {
                    alert('شیفتی جهت جاگذاری، کپی نشده است.');
                }
            }

            function saveSchedule(schedule, copiedPersonnelId) {
                fetch('{{ route('schedule.paste') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        title: schedule.title,
                        from_date: schedule.from_date,
                        to_date: schedule.to_date,
                        schedule_date_id: schedule.schedule_date_id,
                        room_id: schedule.room_id,
                        personnel_id: schedule.personnel_id,
                        service_id: schedule.medical_service_id,
                        is_appointable: true,
                        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        check_personnel: copiedPersonnelId
                    })
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                    return response.json()
                })
                .then(data => {
                    alert(data.message);
                })
                .catch(error => console.error('Fetch Error:', error));
            }

            // Function to display selected personnel in the main table
            function displaySelectedPersonnel(personnel) {
                const { startOfWeek, endOfWeek } = getWeekRange(currentDate);

                scheduleTableHead.classList.remove('hidden');

                let rows = `
                    <tr class="bg-white border border-gray-300 rounded-b-lg">
                        <td class="px-6 py-4 bg-gray-100 font-bold">${personnel.full_name}</td>
                `;

                for (let date = new Date(startOfWeek); date <= endOfWeek; date.setDate(date.getDate() + 1)) {
                    const jalaaliDate = jalaali.toJalaali(date);
                    const gregorianDate = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}`;
                    const calendarEntry = calendars.find(calendar => calendar.date.split(' ')[0] == gregorianDate);
                    const personnelSchedules = calendarEntry ? calendarEntry.schedules.filter(schedule => schedule.personnel_id === personnel.id) : [];

                    if (calendarEntry && calendarEntry.is_holiday) {
                        rows += `
                            <td class="px-6 py-4 border border-gray-200">
                                <p class="text-red-500 font-bold">تعطیل</p>
                            </td>
                        `;
                    } else if (calendarEntry && !calendarEntry.is_holiday) {
                        if (personnelSchedules.length > 0) {
                            rows += `<td class="px-6 py-4 border border-gray-200">`;
                            personnelSchedules.forEach(schedule => {
                                const fromDateTimeValue = `${schedule.from_date.split(' ')[1].split(':')[0]}:${schedule.from_date.split(' ')[1].split(':')[1]}`;
                                const toDateTimeValue = `${schedule.to_date.split(' ')[1].split(':')[0]}:${schedule.to_date.split(' ')[1].split(':')[1]}`;

                                rows += `
                                    <!-- main view -->
                                    <div class="flex flex-col gap-1 my-3">
                                        <p>
                                            عنوان شیفت: <strong>${schedule.title}</strong>
                                        </p>
                                        <p>
                                            از ساعت: <strong>${fromDateTimeValue}</strong>
                                        </p>
                                        <p>
                                            تا ساعت: <strong>${toDateTimeValue}</strong>
                                        </p>
                                        <p>
                                            در اتاق: <strong>${schedule.room.title}</strong>
                                        </p>
                                        <p>
                                            خدمت درمانی: <strong>${schedule.service.name}</strong>
                                        </p>
                                    </div>
                                    <!-- Modal -->
                                    ${editScheduleModalGenerator(personnel, schedule, calendarEntry, jalaaliDate)}

                                `;
                            });
                            rows += `
                                <div class="flex flex-col items-center justify-center w-full">
                                    <hr class="w-64 h-px my-4 bg-gray-300 border-0 dark:bg-gray-700">
                                    <div class="flex justify-between gap-2">
                                        ${addScheduleModalGenerator(personnel, calendarEntry, jalaaliDate)}
                                        <button class="text-purple-600 hover:text-purple-800 transition" type="button" onclick="pasteSchedule(${personnel.id}, '${gregorianDate}')">
                                            <x-icons.paste-icon />
                                        </button>
                                        <button type="button" class="text-green-500 hover:text-green-700 text-sm font-semibold" onclick="copySchedule(${personnel.id}, '${gregorianDate}')">
                                            <x-icons.copy-icon />
                                        </button>
                                    </div>
                                </div>
                            </td>`;
                        } else {
                            rows += `<td class="px-6 py-4 border border-gray-200">
                                ${addScheduleModalGenerator(personnel, calendarEntry, jalaaliDate)}
                                <button class="text-purple-600 hover:text-purple-800 transition" type="button" onclick="pasteSchedule(${personnel.id}, '${gregorianDate}')">
                                    <x-icons.paste-icon />
                                </button>
                            </td>`;
                        }
                    } else {
                        rows += `<td class="px-6 py-4 border border-gray-200">روز کاری ثبت نشده است</td>`;
                    }

                }

                rows += `</tr>`;

                scheduleTableBody.innerHTML += rows;

                // open button
                document.querySelectorAll("[id^=open-add-schedule-modal-]").forEach(btn => {
                    btn.addEventListener("click", (event) => {
                        const identifier = event.currentTarget.id.replace("open-add-schedule-modal-", "");
                        const relatedPersonnel = personnels.find(personnel => personnel.id == identifier.split('-')[1]);
                        const addScheduleModal = document.getElementById(`add-schedule-modal-${identifier}`);

                        if (addScheduleModal) {
                            addScheduleModal.classList.remove("hidden");
                            generateServicesSelectBox(relatedPersonnel, identifier);
                            generateRoomsSelectBox(identifier);
                        }
                    });
                });

                // close button
                document.querySelectorAll("[id^=close-add-schedule-modal-]").forEach(btn => {
                    btn.addEventListener("click", (event) => {
                        const identifier = event.currentTarget.id.replace("close-add-schedule-modal-", "");
                        const addScheduleModal = document.getElementById(`add-schedule-modal-${identifier}`);

                        if (addScheduleModal) {
                            addScheduleModal.classList.add("hidden");
                        }
                    });
                });

                // cancel button
                document.querySelectorAll("[id^=cancel-add-schedule-btn-]").forEach(btn => {
                    btn.addEventListener("click", (event) => {
                        const identifier = event.currentTarget.id.replace("cancel-add-schedule-btn-", "");
                        const addScheduleModal = document.getElementById(`add-schedule-modal-${identifier}`);

                        if (addScheduleModal) {
                            addScheduleModal.classList.add("hidden");
                        }
                    });
                });

            // actions related to edit modal
                // open button
                document.querySelectorAll("[id^=open-edit-schedule-modal-]").forEach(btn => {
                    btn.addEventListener("click", (event) => {
                        document.querySelectorAll('[id^="edit-service-"]').forEach(serviceBox => {
                            const scheduleId = serviceBox.id.replace('edit-service-', '');
                            const schedule = schedules.find(schedule => schedule.id == scheduleId);
                            let options = '';

                            schedule.personnel.medicalservices.forEach(service => {
                                options += `<option value="${service.id}" ${schedule.service.id == service.id ? 'selected' : ''}>${service.name}</option>`;
                            });
                            serviceBox.innerHTML = options;
                        });

                        document.querySelectorAll('[id^="edit-room-"]').forEach(roomBox => {
                            const scheduleId = roomBox.id.replace('edit-room-', '');
                            const schedule = schedules.find(schedule => schedule.id == scheduleId);
                            let options = '';
                            rooms.forEach(room => {
                                options += `<option value="${room.id}" ${schedule.room.id == room.id ? 'selected' : ''}>${room.title}</option>`;
                            });
                            roomBox.innerHTML = options;
                        });

                        const identifier = event.currentTarget.id.replace("open-edit-schedule-modal-", "");
                        const editScheduleModal = document.getElementById(`edit-schedule-modal-${identifier}`);

                        if (editScheduleModal) {
                            editScheduleModal.classList.remove("hidden");
                        }
                    });
                });

                // close button
                document.querySelectorAll("[id^=close-edit-schedule-modal-]").forEach(btn => {
                    btn.addEventListener("click", (event) => {
                        const identifier = event.currentTarget.id.replace("close-edit-schedule-modal-", "");
                        const editScheduleModal = document.getElementById(`edit-schedule-modal-${identifier}`);

                        if (editScheduleModal) {
                            editScheduleModal.classList.add("hidden");
                        }
                    });
                });

                // cancel button
                document.querySelectorAll("[id^=cancel-edit-schedule-btn-]").forEach(btn => {
                    btn.addEventListener("click", (event) => {
                        const identifier = event.currentTarget.id.replace("cancel-edit-schedule-btn-", "");
                        const editScheduleModal = document.getElementById(`edit-schedule-modal-${identifier}`);

                        if (editScheduleModal) {
                            editScheduleModal.classList.add("hidden");
                        }
                    });
                });

            }

            // Automatically open add-schedule-modal if validation error exists
            if (addScheduleValidation) {
                const selectedPersonnel = personnels.find(personnel => personnel.id == selectedPersonnelId);
                if (selectedPersonnel) {
                    displaySelectedPersonnel(selectedPersonnel);

                    // Wait for the DOM to update before opening the modal
                    setTimeout(() => {
                        const modalId = `add-schedule-modal-${addScheduleValidation}`;
                        const addScheduleModal = document.getElementById(modalId);

                        if (addScheduleModal) {
                            addScheduleModal.classList.remove("hidden");
                            generateServicesSelectBox(selectedPersonnel, addScheduleValidation);
                            generateRoomsSelectBox(addScheduleValidation);

                            // show validation errors if exists
                            const validationErrors = @json($errors->toArray());
                            const nameId = `${addScheduleValidation.split('-')[0]}_${addScheduleValidation.split('-')[1]}`;
                            const titleValidation = `title_${nameId}`;
                            const fromDateValidation = `from_date_${nameId}`;
                            const toDateValidation = `to_date_${nameId}`;
                            const roomValidation = `room_${nameId}`;
                            const serviceValidation = `service_${nameId}`;

                            if (validationErrors[titleValidation]) {
                                document.querySelector(`[name="${titleValidation}"]`).parentElement.nextElementSibling.innerText = `${validationErrors[titleValidation][0]}`;
                            }
                            if (validationErrors[fromDateValidation]) {
                                document.querySelector(`[name="${fromDateValidation}"]`).parentElement.nextElementSibling.innerText = `${validationErrors[fromDateValidation][0]}`;
                            }
                            if (validationErrors[toDateValidation]) {
                                document.querySelector(`[name="${toDateValidation}"]`).parentElement.nextElementSibling.innerText = `${validationErrors[toDateValidation][0]}`;
                            }
                            if (validationErrors[roomValidation]) {
                                document.querySelector(`[name="${roomValidation}"]`).parentElement.nextElementSibling.innerText = `${validationErrors[roomValidation][0]}`;
                            }
                            if (validationErrors[serviceValidation]) {
                                document.querySelector(`[name="${serviceValidation}"]`).parentElement.nextElementSibling.innerText = `${validationErrors[serviceValidation][0]}`;
                            }

                            // set old values
                            const oldValues = @json(old());

                            if (oldValues[titleValidation]) {
                                document.querySelector(`[name="${titleValidation}"]`).value = oldValues[titleValidation][0];
                            }
                            if (oldValues[fromDateValidation]) {
                                document.querySelector(`[name="${fromDateValidation}"]`).value = oldValues[fromDateValidation];
                            }
                            if (oldValues[toDateValidation]) {
                                document.querySelector(`[name="${toDateValidation}"]`).value = oldValues[toDateValidation];
                            }
                            if (oldValues[roomValidation]) {
                                document.querySelector(`[name="${roomValidation}"]`).value = oldValues[roomValidation][0];
                            }
                            if (oldValues[serviceValidation]) {
                                document.querySelector(`[name="${serviceValidation}"]`).value = oldValues[serviceValidation][0];
                            }
                        }
                    }, 100);
                }
            }

            if (editScheduleValidation) {
                // Wait for the DOM to update before opening the modal
                setTimeout(() => {
                    const editScheduleModalId = `edit-schedule-modal-${editScheduleValidation}`;
                    const editScheduleModal = document.getElementById(editScheduleModalId);

                    if (editScheduleModal) {
                        const schedule = schedules.find(schedule => schedule.id == editScheduleValidation);
                        let serviceOptions = '';
                        let roomOptions = '';

                        schedule.personnel.medicalservices.forEach(service => {
                            serviceOptions += `<option value="${service.id}" ${schedule.service.id == service.id ? 'selected' : ''}>${service.name}</option>`;
                        });
                        document.getElementById(`edit-service-${editScheduleValidation}`).innerHTML = serviceOptions;

                        rooms.forEach(room => {
                            roomOptions += `<option value="${room.id}" ${schedule.room.id == room.id ? 'selected' : ''}>${room.title}</option>`;
                        });
                        document.getElementById(`edit-room-${editScheduleValidation}`).innerHTML = roomOptions;

                        editScheduleModal.classList.remove("hidden");

                        // show validation errors if exists
                        const validationErrors = @json($errors->toArray());
                        const titleValidation = `title_${editScheduleValidation}`;
                        const fromDateValidation = `from_date_${editScheduleValidation}`;
                        const toDateValidation = `to_date_${editScheduleValidation}`;
                        const roomValidation = `room_${editScheduleValidation}`;
                        const serviceValidation = `service_${editScheduleValidation}`;

                        if (validationErrors[titleValidation]) {
                            document.querySelector(`[name="${titleValidation}"]`).parentElement.nextElementSibling.innerText = `${validationErrors[titleValidation][0]}`;
                        }
                        if (validationErrors[fromDateValidation]) {
                            document.querySelector(`[name="${fromDateValidation}"]`).parentElement.nextElementSibling.innerText = `${validationErrors[fromDateValidation][0]}`;
                        }
                        if (validationErrors[toDateValidation]) {
                            document.querySelector(`[name="${toDateValidation}"]`).parentElement.nextElementSibling.innerText = `${validationErrors[toDateValidation][0]}`;
                        }
                        if (validationErrors[roomValidation]) {
                            document.querySelector(`[name="${roomValidation}"]`).parentElement.nextElementSibling.innerText = `${validationErrors[roomValidation][0]}`;
                        }
                        if (validationErrors[serviceValidation]) {
                            document.querySelector(`[name="${serviceValidation}"]`).parentElement.nextElementSibling.innerText = `${validationErrors[serviceValidation][0]}`;
                        }

                        // set old values
                        const oldValues = @json(old());

                        if (oldValues[titleValidation]) {
                            document.querySelector(`[name="${titleValidation}"]`).value = oldValues[titleValidation][0];
                        }
                        if (oldValues[fromDateValidation]) {
                            document.querySelector(`[name="${fromDateValidation}"]`).value = oldValues[fromDateValidation];
                        }
                        if (oldValues[toDateValidation]) {
                            document.querySelector(`[name="${toDateValidation}"]`).value = oldValues[toDateValidation];
                        }
                        if (oldValues[roomValidation]) {
                            document.querySelector(`[name="${roomValidation}"]`).value = oldValues[roomValidation][0];
                        }
                        if (oldValues[serviceValidation]) {
                            document.querySelector(`[name="${serviceValidation}"]`).value = oldValues[serviceValidation][0];
                        }
                    }
                }, 100);
            }


            // functions
            function getPersianMonthsOfYear(jalaliMonth) {
                const monthsOfYear = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
                return monthsOfYear[jalaliMonth - 1];
            }
            function getPersianDaysOfWeak(jalaliDay) {
                const daysOfWeek = ['دو شنبه', 'سه شنبه', 'چهار شنبه', 'پنج شنبه', 'جمعه', 'شنبه', 'یک شنبه'];
                return daysOfWeek[jalaliDay - 1];
            }

            // service options
            function generateServicesSelectBox (personnel, id) {
                const serviceSelectBox = document.getElementById(`service-${id}`);
                let options = '';

                options += `<option disabled selected value="">یکی از خدمات زیر را انتخاب کنید.</option>`;

                personnel.medicalservices.forEach(service => {
                    console.log(service.pivot.personnel_id , personnel.id, service, personnel)
                    if (service.pivot.personnel_id == personnel.id) {
                        options += `<option value="${service.id}">${service.name}</option>`;
                    }
                });

                serviceSelectBox.innerHTML = options;
            }
            // rooms options
            function generateRoomsSelectBox (id) {
                const roomsSelectbox = document.getElementById(`room-${id}`);
                let options = '';

                options += `<option disabled selected value="">یکی از اتاقهای زیر را انتخاب کنید.</option>`;

                rooms.forEach(room => {
                    options += `<option value="${room.id}">${room.title}</option>`;
                });

                roomsSelectbox.innerHTML = options;
            }

            // add schedule modal generator
            function addScheduleModalGenerator(personnel, calendarEntry, jalaaliDate) {
                return `<div id="add-schedule-modal-${calendarEntry.id}-${personnel.id}" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
                                        <div class="max-w-4xl w-full bg-white p-6 rounded-lg shadow-lg">
                                            <div class="flex justify-between items-center mb-4">
                                                <h2 class="text-lg font-semibold">افزودن شیفت کاری برای ${personnel.full_name}</h2>
                                                <button id="close-add-schedule-modal-${calendarEntry.id}-${personnel.id}" class="text-gray-500 hover:text-gray-700">X</button>
                                            </div>

                                            <div id="add-schedule-body-${calendarEntry.id}-${personnel.id}" class="mt-5">
                                                <form class="w-full " action="{{ route('schedule.store') }}" method="POST">
                                                    @csrf
                                                    <!-- انتاخب پرسنل -->
                                                    <input type="hidden" name="personnel_id" value="${personnel.id}" />
                                                    <x-app.input.disabled-inputs label="پرسنل انتخاب شده:" name="personnel" value="${personnel.full_name}" />

                                                    {{-- انتخاب روز و تاریخ --}}
                                                    <input type="hidden" name="schedule_date_id" value="${calendarEntry.id}">
                                                    <x-app.input.disabled-inputs label="تاریخ انتخاب شده" name="schedule_date" value="${jalaaliDate.jd} ${getPersianMonthsOfYear(jalaaliDate.jm)} ${jalaaliDate.jy}" />

                                                    {{-- عنوان شیفت --}}
                                                    <div class="mt-4 flex flex-col items-start w-full">
                                                        <div class="w-full">
                                                            <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                                                عنوان شیفت:*
                                                            </label>
                                                            <input type="text" min="0" = name="title_${calendarEntry.id}_${personnel.id}" placeholder="عنوان شیفت را وارد کنید" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('title_${calendarEntry.id}_${personnel.id}')}}">
                                                        </div>
                                                        <p class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-1"></p>
                                                    </div>

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
                                                                <input type="time" id="from_date" name="from_date_${calendarEntry.id}_${personnel.id}" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ old('from_date_${calendarEntry.id}_${personnel.id}', '09:00') }}" required />
                                                            </div>
                                                            <p class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-1"></p>
                                                        </div>
                                                        <div class="flex w-full flex-col items-start">
                                                            <label for="to_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">تایم پایان شیفت:*</label>
                                                            <div class="relative w-full">
                                                                <div class="absolute inset-y-0 right-0 top-0 flex items-center pr-3.5 pointer-events-none">
                                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                </div>
                                                                <input type="time" id="to_date" name="to_date_${calendarEntry.id}_${personnel.id}" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ old('to_date_${calendarEntry.id}_${personnel.id}', '20:00') }}" required />
                                                            </div>
                                                            <p class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-1"></p>
                                                        </div>
                                                    </div>

                                                    <div class="mx-auto flex justify-between mt-3 gap-3">
                                                        <div class="mt-4 flex flex-col items-start w-full">
                                                            <div class="w-full">
                                                                <label for="service-${calendarEntry.id}-${personnel.id}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">انتخاب خدمت درمانی</label>
                                                                <select name="service_${calendarEntry.id}_${personnel.id}" id="service-${calendarEntry.id}-${personnel.id}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></select>
                                                            </div>
                                                            <p class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-1"></p>
                                                        </div>

                                                        <div class="mt-4 flex flex-col items-start w-full">
                                                            <div class="w-full">
                                                                <label for="room-${calendarEntry.id}-${personnel.id}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">انتخاب اتاق</label>
                                                                <select name="room_${calendarEntry.id}_${personnel.id}" id="room-${calendarEntry.id}-${personnel.id}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></select>
                                                            </div>
                                                            <p class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-1"></p>
                                                        </div>
                                                    </div>
                                                    <div class="mt-6 flex justify-end gap-4">
                                                        <button type="submit" class="rounded-full bg-green-600 dark:bg-green-800 text-white dark:text-white antialiased font-bold hover:bg-green-800 dark:hover:bg-green-900 px-4 py-2 flex items-center justify-between gap-3 transition">
                                                            ثبت شیفت
                                                        </button>
                                                        <button type="button" id="cancel-add-schedule-btn-${calendarEntry.id}-${personnel.id}" class="rounded-full  bg-gray-600 dark:bg-gray-800 text-white dark:text-white antialiased font-bold hover:bg-gray-800 dark:hover:bg-gray-900 px-4 py-2 flex items-center justify-between transition">
                                                            لغو
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Button to Open Modal -->
                                    <button id="open-add-schedule-modal-${calendarEntry.id}-${personnel.id}" class="text-blue-500 hover:text-blue-700 text-sm font-semibold select-personnel"><x-add-icon /></button>
                            `;
            }
            // edit schedule modal generator
            function editScheduleModalGenerator(personnel, schedule, calendarEntry, jalaaliDate) {
                const fromDateTime = schedule.from_date.split(' ')[1];
                const toDateTime = schedule.to_date.split(' ')[1];

                return `<div id="edit-schedule-modal-${schedule.id}" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
                            <div class="max-w-4xl w-full bg-white p-6 rounded-lg shadow-lg">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-lg font-semibold">ویرایش شیفت کاری ${personnel.full_name}</h2>
                                    <button id="close-edit-schedule-modal-${schedule.id}" class="text-gray-500 hover:text-gray-700">X</button>
                                </div>

                                <div id="edit-schedule-body-${schedule.id}" class="mt-5">
                                    <form class="w-full " action="{{ route('schedule.update', '') }}/${schedule.id}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- انتاخب پرسنل -->
                                        <input type="hidden" name="personnel_id" value="${personnel.id}" />
                                        <x-app.input.disabled-inputs label="پرسنل انتخاب شده:" name="personnel" value="${personnel.full_name}" />

                                        {{-- انتخاب روز و تاریخ --}}
                                        <input type="hidden" name="schedule_date_id" value="${calendarEntry.id}">
                                        <x-app.input.disabled-inputs label="تاریخ انتخاب شده" name="schedule_date" value="${jalaaliDate.jd} ${getPersianMonthsOfYear(jalaaliDate.jm)} ${jalaaliDate.jy}" />

                                        {{-- عنوان شیفت --}}
                                        <div class="mt-4 flex flex-col items-start w-full">
                                            <div class="w-full">
                                                <label class="block font-medium text-start text-sm text-gray-700 dark:text-gray-300" >
                                                    عنوان شیفت:*
                                                </label>
                                                <input type="text" min="0" = name="title_${schedule.id}" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="${schedule.title}">
                                            </div>
                                            <p class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-1"></p>
                                        </div>

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
                                                    <input type="time" id="from_date" name="from_date_${schedule.id}" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required value="${fromDateTime}"/>
                                                </div>
                                                <p class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-1"></p>
                                            </div>
                                            <div class="flex w-full flex-col items-start">
                                                <label for="to_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">تایم پایان شیفت:*</label>
                                                <div class="relative w-full">
                                                    <div class="absolute inset-y-0 right-0 top-0 flex items-center pr-3.5 pointer-events-none">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                    <input type="time" id="to_date" name="to_date_${schedule.id}" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required value="${toDateTime}"/>
                                                </div>
                                                <p class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-1"></p>
                                            </div>
                                        </div>

                                        <div class="mx-auto flex justify-between mt-3 gap-3">
                                            <div class="flex flex-col items-start w-full">
                                                <div class="w-full flex flex-col items-start">
                                                    <label for="service-${schedule.id}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">انتخاب خدمت درمانی*</label>
                                                    <select name="service_${schedule.id}" id="edit-service-${schedule.id}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></select>
                                                </div>
                                                <p class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-1"></p>
                                            </div>

                                            <div class="flex flex-col items-start w-full">
                                                <div class="w-full flex flex-col items-start">
                                                    <label for="room-${schedule.id}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">انتخاب اتاق*</label>
                                                    <select name="room_${schedule.id}" id="edit-room-${schedule.id}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></select>
                                                </div>
                                                <p class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-1"></p>
                                            </div>
                                        </div>
                                        <div class="mt-6 flex justify-end gap-4">
                                            <button type="submit" class="rounded-full bg-yellow-600 dark:bg-yellow-800 text-white dark:text-white antialiased font-bold hover:bg-yellow-800 dark:hover:bg-yellow-900 px-4 py-2 flex items-center justify-between gap-3 transition">
                                                ویرایش شیفت
                                            </button>
                                            <button type="button" id="cancel-edit-schedule-btn-${schedule.id}" class="rounded-full  bg-gray-600 dark:bg-gray-800 text-white dark:text-white antialiased font-bold hover:bg-gray-800 dark:hover:bg-gray-900 px-4 py-2 flex items-center justify-between transition">
                                                لغو
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-center items-center gap-2">
                            <form action="{{ route('schedule.destroy', '') }}/${schedule.id}" class="flex items-center justify-center" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold trash-toggle">
                                    <x-icons.trash />
                                </button>
                            </form>
                            <button id="open-edit-schedule-modal-${schedule.id}" class="text-yellow-500 hover:text-yellow-700 text-sm font-semibold select-personnel"><x-edit-icon /></button>
                        </div>

                `;
            }

            function findPersonnelWithShifts() {
                const { startOfWeek, endOfWeek } = getWeekRange(currentDate);

                const personnelWithShifts = [];
                currentRenderedSchedules = [];

                for (let date = new Date(startOfWeek); date <= endOfWeek; date.setDate(date.getDate() + 1)) {
                    const gregorianDate = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}`;
                    const calendarEntry = calendars.find(calendar => calendar.date.split(' ')[0] == gregorianDate);

                    if (calendarEntry && calendarEntry.schedules.length > 0) {
                        calendarEntry.schedules.forEach(schedule => {
                            const personnelId = schedule.personnel_id;
                            const selectedPersonnel = personnels.find(personnel => personnel.id == personnelId);
                            if (selectedPersonnel && !personnelWithShifts.includes(selectedPersonnel)) {
                                personnelWithShifts.push(selectedPersonnel);
                            }
                            currentRenderedSchedules.push(schedule);
                        });
                    }
                }

                // Add newly added personnel to the list if they were added in the current week
                newlyAddedPersonnel.forEach(({ personnel, startOfWeek: addedStartOfWeek, endOfWeek: addedEndOfWeek }) => {
                    if (startOfWeek.getTime() === addedStartOfWeek.getTime() && endOfWeek.getTime() === addedEndOfWeek.getTime()) {
                        if (!personnelWithShifts.includes(personnel)) {
                            personnelWithShifts.push(personnel);
                        }
                    }
                });

                scheduleTableBody.innerHTML = ''; // Clear the table before rendering

                if (personnelWithShifts.length === 0) {
                    scheduleTableBody.innerHTML = `<tr class="bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-b-lg">
                                                    <td colspan="8" class="px-6 py-4">
                                                        هیچ شیفتی ثبت نشده است. <br><br>
                                                        برای افزودن شیفت، ابتدا پرسنل موردنظر را انتخاب کنید.
                                                    </td>
                                                </tr>`;
                } else {
                    personnelWithShifts.forEach(personnel => displaySelectedPersonnel(personnel));
                }
            }

            // filter modal
            const filterModal = document.getElementById('filter-modal');
            const openFilterModalBtn = document.getElementById('open-filter');
            const closeFilterModalBtn = document.getElementById('close-filter');
            const applyFilterBtn = document.getElementById('apply-filter');
            const searchPersonnelFilter = document.getElementById('search-personnel-filter');
            const personnelFilter = document.getElementById('personnel-filter');
            const serviceFilter = document.getElementById('service-filter');
            const roomFilter = document.getElementById('room-filter');

            openFilterModalBtn.addEventListener('click', () => {
                filterModal.classList.remove('hidden');
                filterPersonnelBox();
                filterServiceBox();
                filterRoomBox();
            });

            closeFilterModalBtn.addEventListener('click', () => {
                filterModal.classList.add('hidden');
            });

            applyFilterBtn.addEventListener('click', () => {
                filterSchedules();
                filterModal.classList.add('hidden');
            });

            function filterPersonnelBox() {
                const selectedPersonnel = personnelFilter.value;
                let options = '<option value="">همه پرسنل</option>';
                personnels.forEach(personnel => {
                    options += `<option value="${personnel.id}" ${personnel.id == selectedPersonnel ? 'selected' : ''}>${personnel.full_name}</option>`;
                });
                personnelFilter.innerHTML = options;
            }

            function filterServiceBox() {
                const selectedService = serviceFilter.value;
                let options = '<option value="">همه خدمات درمانی</option>';
                personnels.forEach(personnel => {
                    personnel.medicalservices.forEach(service => {
                        options += `<option value="${service.id}" ${service.id == selectedService ? 'selected' : ''}>${service.name}</option>`;
                    });
                });
                serviceFilter.innerHTML = options;
            }

            function filterRoomBox() {
                const selectedRoom = roomFilter.value;
                let options = '<option value="">همه اتاق‌ها</option>';
                rooms.forEach(room => {
                    options += `<option value="${room.id}" ${room.id == selectedRoom ? 'selected' : ''}>${room.title}</option>`;
                });
                roomFilter.innerHTML = options;
            }

            function filterSchedules() {
                const searchQuery = searchPersonnelFilter.value.toLowerCase();
                const selectedPersonnel = personnelFilter.value;
                const selectedService = serviceFilter.value;
                const selectedRoom = roomFilter.value;

                const filteredSchedules = currentRenderedSchedules.filter(schedule => {
                    const matchesSearch = schedule.personnel.full_name.toLowerCase().includes(searchQuery) || schedule.title.toLowerCase().includes(searchQuery);
                    const matchesPersonnel = selectedPersonnel === '' || schedule.personnel_id == selectedPersonnel;
                    const matchesService = selectedService === '' || schedule.medical_service_id == selectedService;
                    const matchesRoom = selectedRoom === '' || schedule.room_id == selectedRoom;

                    return matchesSearch && matchesPersonnel && matchesService && matchesRoom;
                });

                displayFilteredSchedules(filteredSchedules);
            }

            function displayFilteredSchedules(filteredSchedules) {
                // Group schedules by personnel
                const groupedSchedules = filteredSchedules.reduce((acc, schedule) => {
                    if (!acc[schedule.personnel_id]) {
                        acc[schedule.personnel_id] = [];
                    }
                    acc[schedule.personnel_id].push(schedule);
                    return acc;
                }, {});

                // Clear and render the filtered schedules in the table
                scheduleTableBody.innerHTML = '';
                if (Object.keys(groupedSchedules).length === 0) {
                    scheduleTableBody.innerHTML = `<tr class="bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-b-lg">
                                                    <td colspan="8" class="px-6 py-4">
                                                        موردی یافت نشد.
                                                    </td>
                                                </tr>`;
                } else {
                    Object.values(groupedSchedules).forEach(schedules => {
                        const personnel = personnels.find(personnel => personnel.id == schedules[0].personnel.id);
                        displaySelectedPersonnel(personnel);
                    });
                }
            }


            // mini calnedar
            const prevWeekBtn = document.getElementById('prev-week');
            const nextWeekBtn = document.getElementById('next-month');
            const currentWeekBtn = document.getElementById('current-week');
            const currentMonthAndYear = document.getElementById('current-month-year');

            prevWeekBtn.addEventListener('click', () => {
                currentDate.setDate(currentDate.getDate() - 7);
                findPersonnelWithShifts();
            });

            nextWeekBtn.addEventListener('click', () => {
                currentDate.setDate(currentDate.getDate() + 7);
                findPersonnelWithShifts();
            });

            currentWeekBtn.addEventListener('click', () => {
                currentDate = new Date();
                findPersonnelWithShifts();
            });

            findPersonnelWithShifts();
            currentMonthAndYear.innerHTML = `${getPersianDaysOfWeak(currentDate.getDay())} ${todayDate.jd} ${getPersianMonthsOfYear(todayDate.jm)}`;

            // mini calendar
            const miniCalendarModal = document.getElementById('mini-calendar-modal');
            const openMiniCalendarModalBtn = document.getElementById('open-mini-calendar');
            const closeMiniCalendarModalBtn = document.getElementById('close-mini-calendar');
            const searchInput = document.getElementById('search-date');
            const miniCalendarElement = document.getElementById('mini-calendar-table-body');
            const miniCalendarHead = document.getElementById('mini-calendar-head');
            const todayMini = document.getElementById('today-mini');

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

            openMiniCalendarModalBtn.addEventListener('click', () => {
                miniCalendarModal.classList.remove('hidden');
                generateMiniCalendar(jalaliDate.jy, jalaliDate.jm);
            });

            closeMiniCalendarModalBtn.addEventListener('click', () => {
                miniCalendarModal.classList.add('hidden');
            });

            searchInput.addEventListener('change', (event) => {
                const selectedDate = event.target.value.split('-');
                if (selectedDate.length === 3) {
                    const gregorianDate = jalaali.toGregorian(parseInt(selectedDate[0]), parseInt(selectedDate[1]), parseInt(selectedDate[2]));
                    currentDate = new Date(gregorianDate.gy, gregorianDate.gm - 1, gregorianDate.gd);
                    jalaliDate = jalaali.toJalaali(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());
                    findPersonnelWithShifts();
                    miniCalendarModal.classList.add('hidden');
                }
            });

            // route without refreshing through months in mini calendar
            document.getElementById('prev-month-mini').addEventListener('click', function () {
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
                } else if (currentView === 'decade') {
                    jalaliDate.jy -= 10;
                    if (jalaliDate.jy < jalaali.toJalaali(new Date()).jy - 20) {
                        jalaliDate.jy = jalaali.toJalaali(new Date()).jy - 20;
                    }
                    miniCalendarElement.innerHTML = generateDecadeView(jalaliDate.jy);
                    document.getElementById('current-month-mini').innerText = `${jalaliDate.jy - 6} - ${jalaliDate.jy + 5}`;
                    miniCalendarHead.innerHTML = `<tr>
                                <th colspan="8" class="p-2">دهه ${jalaliDate.jy - 6} الی ${jalaliDate.jy + 5}</th>
                            </tr>`;
                } else {
                    jalaliDate.jm--;
                    if (jalaliDate.jm < 1) {
                        jalaliDate.jm = 12;
                        jalaliDate.jy--;
                    }
                    generateMiniCalendar(jalaliDate.jy, jalaliDate.jm);
                }
            });

            document.getElementById('next-month-mini').addEventListener('click', function () {
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
                } else if (currentView === 'decade') {
                    jalaliDate.jy += 10;
                    if (jalaliDate.jy > jalaali.toJalaali(new Date()).jy + 20) {
                        jalaliDate.jy = jalaali.toJalaali(new Date()).jy + 20;
                    }
                    miniCalendarElement.innerHTML = generateDecadeView(jalaliDate.jy);
                    document.getElementById('current-month-mini').innerText = `${jalaliDate.jy - 6} - ${jalaliDate.jy + 5}`;
                    miniCalendarHead.innerHTML = `<tr>
                                <th colspan="8" class="p-2">دهه ${jalaliDate.jy - 6} الی ${jalaliDate.jy + 5}</th>
                            </tr>`;
                } else {
                    jalaliDate.jm++;
                    if (jalaliDate.jm > 12) {
                        jalaliDate.jm = 1;
                        jalaliDate.jy++;
                    }
                    generateMiniCalendar(jalaliDate.jy, jalaliDate.jm);
                }
            });

            document.getElementById('current-month-mini').addEventListener('click', function () {
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

            todayMini.addEventListener('click', function () {
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

            document.addEventListener('click', function (event) {
                if (event.target.matches('[data-month]')) {
                    const jalaliYear = jalaliDate.jy;
                    const jalaliMonth = parseInt(event.target.getAttribute('data-month'));
                    selectMonth(jalaliYear, jalaliMonth);
                } else if (event.target.matches('[data-year]')) {
                    const year = parseInt(event.target.getAttribute('data-year'));
                    selectYear(year);
                } else if (event.target.matches('[data-date]')) {
                    const selectedDate = event.target.getAttribute('data-date').split('-');
                    const gregorianDate = new Date(selectedDate[0], selectedDate[1] - 1, selectedDate[2]);
                    jalaliDate = jalaali.toJalaali(gregorianDate.getFullYear(), gregorianDate.getMonth() + 1, gregorianDate.getDate());
                    currentDate = new Date(gregorianDate.getFullYear(), gregorianDate.getMonth(), gregorianDate.getDate());
                    findPersonnelWithShifts();
                    miniCalendarModal.classList.add('hidden');
                }
            });

            // timer
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

            // Update the timer every second
            setInterval(updateLiveTimer, 1000);

            // Initial call to display the timer immediately
            updateLiveTimer();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jalaali-js/dist/jalaali.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jalaali-js/dist/jalaali.min.js"></script>
    <script src="https://unpkg.com/jalaali-js/dist/jalaali.js"></script>
    <script src="https://unpkg.com/jalaali-js/dist/jalaali.min.js"></script>
</x-app-layout>

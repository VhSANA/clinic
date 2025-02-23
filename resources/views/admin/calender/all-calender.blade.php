@extends('admin.layouts.master')

@section('content')
<div class="w-full">
    <div class="bg-primary text-white shadow rounded-lg">
      <div class="p-0">
        <!-- THE CALENDAR -->
        <div id="calendar" class="flex flex-col bg-white text-gray-800">
            <div class="flex justify-between items-center gap-3">
                <div class="flex justify-center items-center gap-3">
                    <div class="" id="prev-month">
                        <x-app.button.right-arrow>ماه قبلی</x-app.button.right-arrow>
                    </div>
                    <div id="current-month" class="flex justify-between items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition cursor-pointer">ماه جاری</div>
                    <div class="" id="next-month">
                        <x-app.button.left-arrow >ماه بعدی</x-app.button.left-arrow>
                    </div>
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
                    <button id="open-mini-calendar" class="flex justify-between items-center gap-2 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-3  mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800 transition cursor-pointer">جستجوی تاریخ <x-icons.search /></button>
                </div>
                <div class="flex justify-between items-center gap-5 ml-2">
                    <div class="flex flex-col justify-between items-center">
                        <div id="live-timer" class="text-lg font-bold">
                            <span id="hours"></span><span id="colon" class="hidden">:</span><span id="minutes"></span>
                        </div>
                        <hr class="w-full h-1 mx-auto my-1 bg-gray-300 border-0 rounded-sm">
                        <h2 id="current-month-year" class="text-lg font-bold mb-2"></h2>
                    </div>
                </div>
            </div>
            <table class="w-full text-sm text-left rtl:text-center text-gray-800 dark:text-gray-400 rounded-lg overflow-hidden">
                <thead class="text-xs text-white uppercase bg-gray-800 dark:bg-gray-200 dark:text-gray-400 rounded-t-lg">
                    <tr class="rounded-t-lg border-b border-gray-300">
                        <th scope="col" class="px-6 py-3 border-r border-gray-300">شنبه</th>
                        <th scope="col" class="px-6 py-3 border-r border-gray-300">یکشنبه</th>
                        <th scope="col" class="px-6 py-3 border-r border-gray-300">دوشنبه</th>
                        <th scope="col" class="px-6 py-3 border-r border-gray-300">سه شنبه</th>
                        <th scope="col" class="px-6 py-3 border-r border-gray-300">چهارشنبه</th>
                        <th scope="col" class="px-6 py-3 border-r border-gray-300">پنج شنبه</th>
                        <th scope="col" class="px-6 py-3 border-r border-gray-300">جمعه</th>
                    </tr>
                </thead>
                <tbody id="calendar-table-body">
                    <tr class="bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-b-lg">
                        <td colspan="8" class="px-6 py-4">
                            لطفا کمی صبر کنید ...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendars = @json($calendars);
        const mainCalendar = document.getElementById('calendar-table-body');
        const currentDate = new Date();
        const todayDate =jalaali.toJalaali(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());
        let jalaliDate = jalaali.toJalaali(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());
        const currentMonthAndYear = document.getElementById('current-month-year');

        function generateMainCalendar(jalaliYear, jalaliMonth) {
            const gregorianFirstDay = jalaali.toGregorian(jalaliYear, jalaliMonth, 1);
            const dateObj = new Date(gregorianFirstDay.gy, gregorianFirstDay.gm - 1, gregorianFirstDay.gd);
            const firstDayOfWeekGregorian = dateObj.getDay(); // 0 (Sunday) to 6 (Saturday)
            const firstDayOfMonth = (firstDayOfWeekGregorian + 1) % 7; // Adjust to Persian week (Saturday=0)

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
                        // Previous month's days
                        const day = prevMonthDays - (firstDayOfMonth - j - 1);
                        row += `<td class="border p-4 text-center rounded m-2 bg-white opacity-20 border-gray-300">${day}</td>`;
                    } else if (dateCounter <= totalDays) {
                        // Current month's days
                        const gregorianDate = jalaali.toGregorian(jalaliYear, jalaliMonth, dateCounter);
                        const dateString = `${gregorianDate.gy}-${String(gregorianDate.gm).padStart(2, '0')}-${String(gregorianDate.gd).padStart(2, '0')}`;
                        const calendarEntry = calendars.find(cal => cal.date.split(' ')[0] === dateString);
                        const content = calendarEntry ? showWorkDay(gregorianDate, calendarEntry) : addToWorkDay(gregorianDate, calendarEntry);
                        row += `<td class="border p-4 text-center rounded m-2 transition ${j === 6 ? 'bg-gray-200' : 'bg-gray-50'} border-gray-300 hover:bg-gray-300">
                            ${content}
                            ${dateCounter} ${getPersianMonthsOfYear(jalaliYear, jalaliMonth)} ${jalaliYear}
                        </td>`;
                        dateCounter++;
                    } else {
                        // Next month's days
                        const nextMonthDay = dateCounter - totalDays;
                        row += `<td class="border p-4 text-center rounded m-2 bg-white opacity-20 border-gray-300">${nextMonthDay}</td>`;
                        dateCounter++;
                    }
                }
                row += '</tr>';
                rows += row;
                if (dateCounter > totalDays) break;
            }

            mainCalendar.innerHTML = rows;
        }

        generateMainCalendar(jalaliDate.jy, jalaliDate.jm);

        function getPersianMonthsOfYear(jalaliYear, jalaliMonth) {
            const monthsOfYear = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
            return monthsOfYear[jalaliMonth - 1];
        }

        function getPersianDaysOfWeak(jalaliDay) {
            const daysOfWeek = ['یک شنبه', 'دو شنبه', 'سه شنبه', 'چهار شنبه', 'پنج شنبه', 'جمعه', 'شنبه',];
            return daysOfWeek[jalaliDay - 1];
        }

        currentMonthAndYear.innerText = `${getPersianDaysOfWeak(todayDate.jd)} ${todayDate.jd} ${getPersianMonthsOfYear(todayDate.jy, todayDate.jm)}`;

        // route without refreshing through months
        document.getElementById('prev-month').addEventListener('click', function () {
            jalaliDate.jm--;
            if (jalaliDate.jm < 1) {
                jalaliDate.jm = 12;
                jalaliDate.jy--;
            }
            generateMainCalendar(jalaliDate.jy, jalaliDate.jm);
        });

        document.getElementById('next-month').addEventListener('click', function () {
            jalaliDate.jm++;
            if (jalaliDate.jm > 12) {
                jalaliDate.jm = 1;
                jalaliDate.jy++;
            }
            generateMainCalendar(jalaliDate.jy, jalaliDate.jm);
        });

        document.getElementById('current-month').addEventListener('click', function () {
            jalaliDate = jalaali.toJalaali(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());
            generateMainCalendar(jalaliDate.jy, jalaliDate.jm);
        });

        // Mini Calendar Modal
        const modal = document.getElementById('mini-calendar-modal');
        const openModalBtn = document.getElementById('open-mini-calendar');
        const closeModalBtn = document.getElementById('close-mini-calendar');
        const searchInput = document.getElementById('search-date');
        const miniCalendarElement = document.getElementById('mini-calendar-table-body');
        const miniCalendarHead = document.getElementById('mini-calendar-head');
        const todayMini = document.getElementById('today-mini');

        function generateMiniCalendar(jalaliYear, jalaliMonth) {
            const gregorianFirstDay = jalaali.toGregorian(jalaliYear, jalaliMonth, 1);
            const dateObj = new Date(gregorianFirstDay.gy, gregorianFirstDay.gm - 1, gregorianFirstDay.gd);
            const firstDayOfWeekGregorian = dateObj.getDay(); // 0 (Sunday) to 6 (Saturday)
            const firstDayOfMonth = (firstDayOfWeekGregorian + 1) % 7; // Adjust to Persian week (Saturday=0)

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

            document.getElementById('current-month-mini').innerText = `${getPersianMonthsOfYear(jalaliYear, jalaliMonth)} ${jalaliYear}`;
        }

        openModalBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            generateMiniCalendar(jalaliDate.jy, jalaliDate.jm);
        });

        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        searchInput.addEventListener('change', (event) => {
            const selectedDate = event.target.value.split('-');
            if (selectedDate.length === 3) {
                jalaliDate.jy = parseInt(selectedDate[0]);
                jalaliDate.jm = parseInt(selectedDate[1]);
                generateMainCalendar(jalaliDate.jy, jalaliDate.jm);
                modal.classList.add('hidden');
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
            document.getElementById('current-month-mini').innerText = `${getPersianMonthsOfYear(jalaliDate.jy, jalaliDate.jm)} ${jalaliDate.jy}`;
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
            currentMonthMini.innerText = `${getPersianMonthsOfYear(jalaliYear, jalaliMonth)} ${jalaliYear}`;
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
                generateMainCalendar(jalaliDate.jy, jalaliDate.jm);
                modal.classList.add('hidden');
            }
        });

        // helper functions
        function addToWorkDay(gregorianDate) {
            return `<form action="{{ route('calendar.store')}}" method="post">
                @csrf
                <input name="add_work_date" class="hidden" value="${gregorianDate.gy}-${String(gregorianDate.gm).padStart(2, '0')}-${String(gregorianDate.gd).padStart(2, '0')}">
                <button type="submit" class="text-blue-500 hover:text-blue-700 text-sm font-semibold work-toggle">
                    <x-icons.work />
                </button>
            </form>`;
        }

        function showWorkDay(gregorianDate, calendarEntry) {
            const isHolidayOrNot = calendarEntry.is_holiday;
            const isNotHolidaySvg = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>`;
            const isHolidaySvg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path d="M9.375 3a1.875 1.875 0 0 0 0 3.75h1.875v4.5H3.375A1.875 1.875 0 0 1 1.5 9.375v-.75c0-1.036.84-1.875 1.875-1.875h3.193A3.375 3.375 0 0 1 12 2.753a3.375 3.375 0 0 1 5.432 3.997h3.943c1.035 0 1.875.84 1.875 1.875v.75c0 1.036-.84 1.875-1.875 1.875H12.75v-4.5h1.875a1.875 1.875 0 1 0-1.875-1.875V6.75h-1.5V4.875C11.25 3.839 10.41 3 9.375 3ZM11.25 12.75H3v6.75a2.25 2.25 0 0 0 2.25 2.25h6v-9ZM12.75 12.75v9h6.75a2.25 2.25 0 0 0 2.25-2.25v-6.75h-9Z" />
                        </svg>`;

            return `<div class="flex justify-center items-center gap-2">
                <form action="{{ route('calendar.destroy', '') }}/${calendarEntry.id}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold trash-toggle">
                        <x-icons.trash />
                    </button>
                </form>
                <form action="{{ route('calendar.update', '') }}/${calendarEntry.id}" method="post">
                    @csrf
                    @method('PUT')
                    <button type="submit" id="holiday" class="text-green-500 hover:text-green-700 text-sm font-semibold">
                        ${isHolidayOrNot ? isHolidaySvg : isNotHolidaySvg}
                    </button>
                </form>
            </div>`;
        }

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
@endsection

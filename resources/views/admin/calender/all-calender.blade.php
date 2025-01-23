@extends('admin.layouts.master')

@section('content')
<div class="w-full">
    <div class="bg-primary text-white shadow rounded-lg">
      <div class="p-0">
        <!-- THE CALENDAR -->
        <div id="calendar" class="flex flex-col bg-white text-gray-800">
            <div class="flex flex-col justify-center items-center gap-3">
                <div class="flex justify-center items-center gap-3">
                    <form action="{{ route('calendar.index') }}" method="GET">
                        {{-- @csrf --}}
                        <input type="hidden" name="month" value="{{ $currentDate->subMonths()->getMonth() }}">
                        <input type="hidden" name="year" value="{{ $currentDate->subMonths()->getYear() }}">
                        <x-app.button.right-arrow>ماه قبلی</x-app.button.right-arrow>
                    </form>
                    <a href="{{ route('calendar.index') }}" class="flex justify-between items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition">ماه جاری</a>
                    <form action="{{ route('calendar.index') }}" method="GET">
                        {{-- @csrf --}}
                        <input type="hidden" name="month" value="{{ $currentDate->addMonths()->getMonth() }}">
                        <input type="hidden" name="year" value="{{ $currentDate->addMonths()->getYear() }}">
                        <x-app.button.left-arrow >ماه بعدی</x-app.button.left-arrow>
                    </form>
                </div>
                <div class="flex justify-between items-center gap-5">
                    <h2 class="text-lg font-bold pb-2 mb-2">{{ $monthName }}</h2>
                    <form action="" class="pb-2 mb-2">
                        {{-- TODO change view of date to persian --}}
                        @csrf
                        <button type="button" data-modal-target="timepicker-modal" data-modal-toggle="timepicker-modal" class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                            <svg class="w4 h-4 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                            </svg>
                            برو به تاریخ
                        </button>
                        <!-- Main modal -->
                        <div id="timepicker-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-[23rem] max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            تاریخ مورد نظر را انتخاب کنید
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="timepicker-modal">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-4 pt-0">
                                        <form action="" class="flex flex-col gap-2 w-full">
                                            @csrf
                                            <div class="relative max-w-sm">
                                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                   <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                      <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                                    </svg>
                                                </div>
                                                <input id="datepicker-actions" name="gotodate" datepicker datepicker-buttons datepicker-autoselect-today type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="تاریخ مورد نظر را انتخاب کنید" >
                                            </div>
                                            <div class="flex w-full justify-center items-center gap-3 mt-3">
                                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 w-full ">برو</button>
                                                <button type="button" data-modal-hide="timepicker-modal" class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white w-full rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">لغو</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <table class="table-auto w-full border-t p-4">
                <thead>
                    <tr class="bg-gray-600">
                        <th class="text-center py-3 border border-gray-300 font-medium text-white">شنبه</th>
                        <th class="text-center py-3 border border-gray-300 font-medium text-white">یکشنبه</th>
                        <th class="text-center py-3 border border-gray-300 font-medium text-white">دوشنبه</th>
                        <th class="text-center py-3 border border-gray-300 font-medium text-white">سه شنبه</th>
                        <th class="text-center py-3 border border-gray-300 font-medium text-white">چهارشنبه</th>
                        <th class="text-center py-3 border border-gray-300 font-medium text-white">پنج شنبه</th>
                        <th class="text-center py-3 border border-gray-300 font-medium text-white">جمعه</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="">
                        <!-- Last days of the previous month -->
                        @for ($i = $lastDaysOfPreviousMonth; $i < $daysInPreviousMonth; $i++)
                            <td class="border p-4 text-gray-400 text-center">{{ $i + 1 }}</td>
                        @endfor

                        <!-- Current month days -->
                        @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $persianDate = convertCalendarDayToPersianDate($currentDate, $day);
                            $carbonDate = convertExplodedDate($persianDate);
                            $calendar = $calendars->firstWhere('date', $carbonDate);
                        @endphp
                            <td class="border p-4 text-center rounded m-2 transition {{ ($day + ($daysInPreviousMonth - $lastDaysOfPreviousMonth)) % 7 === 0 ? 'bg-gray-200 border-gray-300 hover:bg-gray-300' : 'bg-gray-50 border-gray-200 hover:bg-gray-100' }}">
                                <div class="text-gray-700 font-bold mb-2">
                                    {{ $day }}
                                </div>
                                @if ($calendar)
                                {{-- @dd($calendar) --}}
                                <div id="trash-view-{{$day}}" class="{{ jdate($calendar->date)->getDay() == $day ? 'flex' : 'hidden' }}  justify-center items-center gap-2">
                                    <form action="{{ route('calendar.destroy', $calendar->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" id="trash-{{$day}}" class="text-red-500 hover:text-red-700 text-sm font-semibold trash-toggle">
                                            <x-icons.trash />
                                        </button>
                                    </form>
                                    <form action="{{ route('calendar.update', $calendar->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input name="add_holiday_date" class="hidden" value="{{ convertCalendarDayToPersianDate($currentDate, $day) }}">
                                        <button type="submit" id="holiday" class="text-green-500 hover:text-green-700 text-sm font-semibold">
                                            <x-icons.holiday isHoliday="{{$calendar->is_holiday == 1 && true}}" />
                                        </button>
                                    </form>
                                </div>
                                @else
                                <div id="work-view-{{$day}}" class="flex justify-around items-center gap-2">
                                    <form action="{{ route('calendar.store')}}" method="post">
                                        @csrf
                                        <input name="add_work_date" class="hidden" value="{{ convertCalendarDayToPersianDate($currentDate, $day) }}">
                                        <button type="submit" id="work-{{$day}}" class="text-blue-500 hover:text-blue-700 text-sm font-semibold work-toggle">
                                            <x-icons.work />
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </td>
                            <!-- Close and start a new row after 7 days -->
                            @if (($day + ($daysInPreviousMonth - $lastDaysOfPreviousMonth)) % 7 === 0)
                                </tr><tr>
                            @endif
                        @endfor

                        <!-- Remaining days of the next month -->
                        @for ($i = 1; $i <= $remainingDays; $i++)
                            <td class="border p-4 text-gray-400 text-center">{{ $i }}</td>

                            <!-- Close the row if it's the last day of the week -->
                            @if (($daysInMonth + $i - 1) % 7 === 0)
                                </tr><tr>
                            @endif
                        @endfor
                    </tr>
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>

  {{-- script for toggling between work and work day's details --}}
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const workToggles = document.querySelectorAll('.work-toggle');
        const trashToggles = document.querySelectorAll('.trash-toggle');

        workToggles.forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                const id = this.id.split('-')[1];
                const workView = document.getElementById(`work-view-${id}`);
                const trashView = document.getElementById(`trash-view-${id}`);

                workView.classList.add('hidden');
                trashView.classList.remove('hidden');
            });
        });

        trashToggles.forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                const id = this.id.split('-')[1];
                const workView = document.getElementById(`work-view-${id}`);
                const trashView = document.getElementById(`trash-view-${id}`);

                workView.classList.remove('hidden');
                trashView.classList.add('hidden');
            });
        });
    });
</script> --}}
@endsection

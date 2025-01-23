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
                        <input type="hidden" name="month" value="{{ $currentDate->subMonths()->getMonth() }}">
                        <input type="hidden" name="year" value="{{ $currentDate->subMonths()->getYear() }}">
                        <x-app.button.right-arrow>ماه قبلی</x-app.button.right-arrow>
                    </form>
                    <a href="{{ route('calendar.index') }}" class="flex justify-between items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition">ماه جاری</a>
                    <form action="{{ route('calendar.index') }}" method="GET">
                        <input type="hidden" name="month" value="{{ $currentDate->addMonths()->getMonth() }}">
                        <input type="hidden" name="year" value="{{ $currentDate->addMonths()->getYear() }}">
                        <x-app.button.left-arrow >ماه بعدی</x-app.button.left-arrow>
                    </form>
                </div>
                <h2 class="text-lg font-bold pb-2 mb-2">{{ $monthName }}</h2>
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

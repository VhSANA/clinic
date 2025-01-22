
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
            <div class="grid grid-cols-7 gap-2 p-4 border-t">
                <div class="text-center font-medium text-gray-600">شنبه</div>
                <div class="text-center font-medium text-gray-600">یکشنبه</div>
                <div class="text-center font-medium text-gray-600">دوشنبه</div>
                <div class="text-center font-medium text-gray-600">سه شنبه</div>
                <div class="text-center font-medium text-gray-600">چهارشنبه</div>
                <div class="text-center font-medium text-gray-600">پنج شنبه</div>
                <div class="text-center font-medium text-gray-600">جمعه</div>

                <!-- Calendar days -->
                {{-- last days of previous month --}}
                @for ($i = $lastDaysOfPreviousMonth; $i <= $daysInPreviousMonth; $i++)
                    <div class="border p-4 text-gray-400 flex justify-center items-center">{{ $i }}</div>
                @endfor
                {{-- days of this month --}}
                @for ($day = 1; $day <= $daysInMonth; $day++)
                    <div class="flex flex-col items-center bg-gray-50 border rounded-lg p-4 hover:shadow-md">
                        <div class="text-gray-700 font-bold mb-2">{{ $day }}</div>
                        <div id="work-view-{{$day}}" class="flex justify-around items-center gap-2">
                            <form action="{{ route('calendar.store')}}" method="post">
                                @csrf
                                <input name="add_work" class="hidden" value="{{ convertCalendarDayToPersianDate($currentDate, $day) }}">
                                <button type="submit" id="work-{{$day}}" class="text-blue-500 hover:text-blue-700 text-sm font-semibold work-toggle">
                                    <x-icons.work />
                                </button>
                            </form>
                        </div>
                        <div id="trash-view-{{$day}}" class="hidden justify-around items-center gap-2">
                            <div class="flex justify-around items-center gap-2">
                                <form action="{{'calendar.destroy'}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" id="trash-{{$day}}" class="text-red-500 hover:text-red-700 text-sm font-semibold trash-toggle">
                                        <x-icons.trash />
                                    </button>
                                </form>
                                <a href="#" class="text-yellow-500 hover:text-yellow-700 text-sm font-semibold">
                                    <x-icons.edit />
                                </a>
                                <a href="#" class="text-green-500 hover:text-green-700 text-sm font-semibold">
                                    <x-icons.holiday />
                                </a>
                            </div>
                        </div>
                    </div>
                @endfor
                {{-- first days of next month --}}
                @for ($i = 1; $i <= $remainingDays; $i++)
                    <div class="border p-4 text-gray-400 flex justify-center items-center">{{ $i }}</div>
                @endfor
            </div>
        </div>
      </div>
    </div>
  </div>

  {{-- script for toggling between work and work day's details --}}
<script>
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
</script>
@endsection

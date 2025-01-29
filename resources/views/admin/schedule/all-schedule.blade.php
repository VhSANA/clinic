@extends('admin.layouts.master')

@section('content')
<div class="w-full">
    <div class="bg-primary text-white shadow rounded-lg">
      <div class="p-0">
        <!-- THE CALENDAR -->
        <div id="calendar" class="flex flex-col bg-white text-gray-800">
            <div class="flex justify-between items-center">
                <div class="flex flex-col justify-start items-start">
                    <div class="flex justify-between items-center gap-2">
                        <form action="{{ route('schedule.index', [ 'personnel_id' => session('personnel_id')]) }}" method="GET">
                            @csrf
                            {{-- @if (session('personnel_id'))
                                <input type="hidden" name="personnel_id" value="{{ session('personnel_id') }}">
                            @endif --}}
                            <input type="hidden" name="week" value="{{ $currentDate->copy()->subWeek()->format('Y-m-d') }}">
                            <x-app.button.right-arrow>هفته قبلی</x-app.button.right-arrow>
                        </form>
                        <form action="{{ route('schedule.index') }}" method="get">
                            @csrf
                            {{-- @if (session('personnel_id'))
                                <input type="hidden" name="personnel_id" value="{{ session('personnel_id') }}">
                            @endif --}}
                            <button type="submit" class="flex justify-between items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition">هفته جاری</button>
                        </form>
                        <form action="{{ route('schedule.index') }}" method="GET">
                            @csrf
                            <input type="hidden" name="week" value="{{ $currentDate->copy()->addWeek()->format('Y-m-d') }}">
                            {{-- @if (session('personnel_id'))
                                <input type="hidden" name="personnel_id" value="{{ session('personnel_id') }}">
                            @endif --}}
                            <x-app.button.left-arrow >هفته بعدی</x-app.button.left-arrow>
                        </form>
                    </div>
                    <form action="{{ route('schedule.index', ['personnel_id' => session('personnel_id') ]) }}" method="GET" class="flex items-center gap-2 mb-2">
                        {{-- TODO change the view of this calender to persian calender but retrieve in georgian --}}
                        @csrf
                        {{-- @if (session('personnel_id'))
                            <input type="hidden" name="personnel_id" value="{{ session('personnel_id') }}">
                        @endif --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <input id="datepicker-range-start" name="gotodate" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="برو به تاریخ">
                        </div>
                        <button type="submit" class="flex justify-between items-center gap-2 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800 transition">برو</button>
                    </form>
                </div>
                <div class="flex">
                    <x-app.modal.choose-personnel button_title="انتخاب پرسنل" modal_title="یکی از پرسنل های زیر را انتخاب کنید" path="App\Models\Personnel" :model="$personnels" />
                </div>
            </div>
            <table class="table-auto w-full border-t p-4">
                <thead>
                    <tr class="bg-gray-600">
                        <th class="text-center py-3 border border-gray-300 font-medium text-white">نام پرسنل</th>
                        @for ($date = $startOfWeek; $date <= ($endOfWeek); $date = $date->addDay())
                            <th class="text-center py-3 border border-gray-300 font-medium text-white">{{ $date->format('%A') }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @if ($schedules->isEmpty())
                        @if (empty($chosen_personnels))
                            <tr>
                                <td class="border p-4 text-center rounded m-2 hover:bg-gray-50 transition">
                                    <div class="text-gray-700 font-bold mb-2">schedule's table is empty پرسنل مورد نظر را انتخاب کنید</div>
                                </td>
                            </tr>
                        @elseif ($chosen_personnels)
                            @foreach ($chosen_personnels as $id)
                                @php
                                    $chosen_personnel = App\Models\Personnel::find($id);
                                @endphp
                                <tr>
                                    <td class="border p-4 text-center rounded m-2 hover:bg-gray-50 transition">
                                        {{ $chosen_personnel->full_name }}
                                    </td>
                                    @for ($date = $startOfWeek; $date <= ($endOfWeek); $date = $date->addDay())
                                        @php
                                            $carbonDate = $date->toCarbon()->toDateTimeString();
                                            $calendarEntry = $calendars->firstWhere('date', $carbonDate);
                                        @endphp
                                        <td class="border p-4 text-center rounded m-2 transition {{ (! is_null($calendarEntry) && $calendarEntry->is_holiday == 1) ? 'bg-gray-200 hover:bg-gray-200' : 'hover:bg-gray-50'}}">
                                            <div class="flex flex-col justify-center items-center">
                                                <div class="text-gray-700 font-bold mb-2">{{ $date->format('%d %B %Y') }}</div>
                                                @if ($calendarEntry && $calendarEntry->is_holiday == 0)
                                                    <x-app.modal.add-work-shift :rooms="$rooms" personnel="{{$chosen_personnel->id}}" schedule_date="{{$calendarEntry->id}}" />
                                                @else
                                                    @if (! is_null($calendarEntry) && $calendarEntry->is_holiday == 1)
                                                        <p class="text-red-500 font-bold">تعطیل</p>
                                                    @else
                                                        <p class="text-red-500 font-bold">روز کاری ثبت نشده است</p>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        @endif
                    @else
                        @if (empty($chosen_personnels))
                            <tr>
                                <td class="border p-4 text-center rounded m-2 hover:bg-gray-50 transition">
                                    <div class="text-gray-700 font-bold mb-2">at least one row in schedule's table پرسنل مورد نظر را انتخاب کنید</div>
                                </td>
                            </tr>
                        @elseif($chosen_personnels)
                            @foreach ($chosen_personnels as $id)
                                @php
                                    $chosen_personnel = App\Models\Personnel::find($id);
                                @endphp
                                <tr>
                                    <td class="border p-4 text-center rounded m-2 hover:bg-gray-50 transition">
                                        {{ $chosen_personnel->full_name }}
                                    </td>
                                    @for ($date = $startOfWeek; $date <= ($endOfWeek); $date = $date->addDay())
                                        @php
                                            $carbonDate = $date->toCarbon()->toDateTimeString();
                                            $calendarEntry = $calendars->firstWhere('date', $carbonDate);
                                        @endphp
                                        <td class="border p-4 text-center rounded m-2 transition {{ (! is_null($calendarEntry) && $calendarEntry->is_holiday == 1) ? 'bg-gray-200 hover:bg-gray-200' : 'hover:bg-gray-50'}}">
                                            <div class="flex flex-col justify-center items-center">
                                                <div class="text-gray-700 font-bold mb-2">{{ $date->format('%d %B %Y') }}</div>
                                                @if ($calendarEntry && $calendarEntry->is_holiday == 0)
                                                    @if ($calendarEntry->schedules->isEmpty())
                                                        <x-app.modal.add-work-shift :rooms="$rooms" personnel="{{$chosen_personnel->id}}" schedule_date="{{$calendarEntry->id}}" />
                                                    @else
                                                        @foreach ($calendarEntry->schedules as $schedule)
                                                            <div class="flex flex-col justify-center items-center">
                                                                <div class="flex flex-col justify-center items-center">
                                                                    <div class="flex flex-col gap-1">
                                                                        <p>
                                                                            عنوان شیفت: <strong>{{ $schedule->title }}</strong>
                                                                        </p>
                                                                        <p>
                                                                            در اتاق: <strong>{{ App\Models\Room::find($schedule->room_id)->title }}</strong>
                                                                        </p>
                                                                        <p>
                                                                            خدمت درمانی: <strong>{{ App\Models\MedicalServices::find($schedule->medical_service_id)->name }}</strong>
                                                                        </p>
                                                                    </div>
                                                                    <div class="mt-3 flex justify-center items-center gap-2">
                                                                        <form action="{{ route('schedule.destroy', 96) }}" class="flex items-center justify-center" method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold trash-toggle">
                                                                                <x-icons.trash />
                                                                            </button>
                                                                        </form>
                                                                        <x-app.modal.edit-work-shift-modal :rooms="$rooms" personnel="{{$chosen_personnel->id}}" schedule_date="{{$calendarEntry->id}}"
                                                                        :schedule="$schedule" />
                                                                    </div>
                                                             </div>
                                                                <div class="flex flex-col items-center justify-center w-full">
                                                                    <hr class="w-64 h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">
                                                                    <x-app.modal.add-work-shift :rooms="$rooms" personnel="{{$chosen_personnel->id}}" schedule_date="{{$calendarEntry->id}}"  />
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @else
                                                    @if (! is_null($calendarEntry) && $calendarEntry->is_holiday == 1)
                                                        <p class="text-red-500 font-bold">تعطیل</p>
                                                    @else
                                                        <p class="text-red-500 font-bold">روز کاری ثبت نشده است</p>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        @endif
                    @endif
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
@endsection

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
                            <div id="calendar" class="flex flex-col bg-white text-gray-800">
                                <div class="flex justify-between items-center">
                                    <div class="flex flex-col justify-start items-start">
                                        <h3 class="font-bold mb-2">تاریخ امروز: {{ jdate(Carbon\Carbon::now())->format('%A، %d %B %Y') }}</h3>
                                        <div class="flex justify-between items-center gap-2">
                                            <form action="{{ route('schedule.index') }}" method="GET">
                                                @csrf
                                                <input type="hidden" name="week" value="{{ $currentDate->copy()->subWeek()->format('Y-m-d') }}">
                                                <input type="hidden" name="selectedWeek" value="{{ $selectedWeek }}">
                                                <x-app.button.right-arrow>هفته قبلی</x-app.button.right-arrow>
                                            </form>
                                            <form action="{{ route('schedule.index') }}" method="get">
                                                @csrf
                                                <button type="submit" class="flex justify-between items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition">هفته جاری</button>
                                            </form>
                                            <form action="{{ route('schedule.index') }}" method="GET">
                                                @csrf
                                                <input type="hidden" name="week" value="{{ $currentDate->copy()->addWeek()->format('Y-m-d') }}">
                                                <input type="hidden" name="selectedWeek" value="{{ $selectedWeek }}">
                                                <x-app.button.left-arrow >هفته بعدی</x-app.button.left-arrow>
                                            </form>
                                        </div>
                                        <form action="{{ route('schedule.index',) }}" method="GET" class="flex items-center gap-2 mb-2">
                                            {{-- TODO change the view of this calender to persian calender but retrieve in georgian --}}
                                            @csrf
                                            <div class="relative">
                                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                                    </svg>
                                                </div>
                                                <input type="hidden" name="selectedWeek" value="{{ $selectedWeek }}">
                                                <input id="datepicker-range-start" name="gotodate" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="برو به تاریخ">
                                            </div>
                                            <button type="submit" class="flex justify-between items-center gap-2 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800 transition">برو</button>
                                        </form>
                                    </div>
                                    <div class="flex">
                                        <x-app.modal.choose-personnel button_title="انتخاب پرسنل" modal_title="یکی از پرسنل های زیر را انتخاب کنید" path="App\Models\Personnel" :model="$personnels" selectedWeek="{{$selectedWeek}}" />
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
                                        @if (empty($chosen_personnels))
                                        <tr>
                                            <td class="border p-4 text-center rounded m-2 ">
                                                <div class="text-gray-700 font-bold mb-2">at least one row in schedule's table پرسنل مورد نظر را انتخاب کنید</div>
                                            </td>
                                        </tr>
                                    @elseif($chosen_personnels)
                                        {{-- prevent from repeated rows for same personnel_id --}}
                                        @php
                                            $unique_personnels = collect($chosen_personnels)->unique();
                                        @endphp
                                        @foreach ($unique_personnels as $id)
                                            @php
                                                $chosen_personnel = App\Models\Personnel::find($id);
                                            @endphp
                                            <tr>
                                                <td class="border p-4 text-center rounded m-2 ">
                                                    {{ $chosen_personnel->full_name }}
                                                </td>
                                                @for ($date = $startOfWeek; $date <= ($endOfWeek); $date = $date->addDay())
                                                    @php
                                                        $carbonDate = $date->toCarbon()->toDateTimeString();
                                                        $calendarEntry = $calendars->firstWhere('date', $carbonDate);
                                                        $personnelSchedules = $calendarEntry ? $calendarEntry->schedules->where('personnel_id', $chosen_personnel->id)->unique() : collect([]);
                                                    @endphp
                                                    <td class="border p-4 text-center rounded m-2 transition {{ (! is_null($calendarEntry) && $calendarEntry->is_holiday == 1) ? 'bg-gray-200 ' : ''}}">
                                                        <div class="flex flex-col justify-center items-center">
                                                            <div class="text-gray-700 font-bold mb-2">{{ $date->format('%d %B %Y') }}</div>
                                                            @if ($calendarEntry && $calendarEntry->is_holiday == 0)
                                                                @if ($personnelSchedules->isEmpty())
                                                                    <div class="flex gap-2">
                                                                        <x-app.modal.add-work-shift :rooms="$rooms" personnel="{{$chosen_personnel->id}}" schedule_date="{{$calendarEntry->id}}" />
                                                                        <form action="{{route('schedule.paste', [ 'personnel' => $chosen_personnel->id, 'date' => $calendarEntry->id] )}}" method="post">
                                                                            @csrf
                                                                            <button
                                                                                class="text-purple-600 hover:text-purple-800 transition" type="submit">
                                                                                <x-icons.paste-icon />
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                @else
                                                                    @php
                                                                        $totalSchedules = count($personnelSchedules);
                                                                    @endphp
                                                                    @foreach ($personnelSchedules as $index => $schedule)
                                                                        <div class="flex flex-col justify-center items-center">
                                                                            <div class="flex flex-col justify-center items-center">
                                                                                @if ($index > 0)
                                                                                    <hr class="w-64 h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">
                                                                                @endif
                                                                                <div class="flex flex-col gap-1">
                                                                                    <p>
                                                                                        عنوان شیفت: <strong>{{ $schedule->title }}</strong>
                                                                                    </p>
                                                                                    <p>
                                                                                        از ساعت: <strong>{{ jdate($schedule->from_date)->format('H:i') }}</strong>
                                                                                    </p>
                                                                                    <p>
                                                                                        تا ساعت: <strong>{{ jdate($schedule->to_date)->format('H:i') }}</strong>
                                                                                    </p>
                                                                                    <p>
                                                                                        در اتاق: <strong>{{ App\Models\Room::find($schedule->room_id)->title }}</strong>
                                                                                    </p>
                                                                                    <p>
                                                                                        خدمت درمانی: <strong>{{ App\Models\MedicalServices::find($schedule->medical_service_id)->name }}</strong>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="mt-3 flex justify-center items-center gap-2">
                                                                                    <form action="{{ route('schedule.destroy', $schedule->id) }}" class="flex items-center justify-center" method="post">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold trash-toggle">
                                                                                            <x-icons.trash />
                                                                                        </button>
                                                                                    </form>
                                                                                    <x-app.modal.edit-work-shift-modal :rooms="$rooms" personnel="{{$chosen_personnel->id}}" schedule_date="{{$calendarEntry->id}}"
                                                                                        :schedule="$schedule" />
                                                                                    <form action="{{ route('schedule.copy', $schedule->id ) }}" class="flex items-center justify-center" method="post">
                                                                                        @csrf
                                                                                        <button type="submit" class="text-green-500 hover:text-green-700 text-sm font-semibold trash-toggle">
                                                                                            <x-icons.copy-icon />
                                                                                        </button>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                            @if ($index == $totalSchedules - 1)
                                                                                <div class="flex flex-col items-center justify-center w-full">
                                                                                    <hr class="w-64 h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">
                                                                                    <div class="flex gap-2">
                                                                                        <x-app.modal.add-work-shift :rooms="$rooms" personnel="{{$chosen_personnel->id}}" schedule_date="{{$calendarEntry->id}}" />
                                                                                        <form action="{{route('schedule.paste', ['personnel' => $chosen_personnel->id, 'date' => $calendarEntry->id] )}}" method="post">
                                                                                            @csrf
                                                                                            <button
                                                                                                class="text-purple-600 hover:text-purple-800 transition" type="submit">
                                                                                                <x-icons.paste-icon />
                                                                                            </button>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
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
</x-app-layout>

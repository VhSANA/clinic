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
                                            <x-app.button.right-arrow>هفته قبلی</x-app.button.right-arrow>
                                            <button type="submit" class="flex justify-between items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition">هفته جاری</button>
                                            <x-app.button.left-arrow >هفته بعدی</x-app.button.left-arrow>
                                        </div>
                                        {{-- <div class="flex justify-between items-center gap-2">
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
                                        </div> --}}
                                        <form action="{{ route('schedule.index',) }}" method="GET" class="flex items-center gap-2 mb-2">
                                            {{-- TODO change the view of this calender to persian calender but retrieve in georgian --}}
                                            @csrf
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
                                        <button id="open-personnel-modal" class="flex justify-between items-center gap-2 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-3  mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800 transition cursor-pointer">انتخاب پرسنل <x-add-icon /></button>
                                    </div>
                                </div>
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
                                {{-- <table class="table-auto w-full border-t p-4">
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
                                </table> --}}
                            </div>
                          </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kqgYncRJQ7spwKfig6It _QPbmfdkSA0FyYKJjf12 __9yfFbPJuEYWBMy4kA9 _T15kfOPGkvwZnqhqKce iHyrYta0Jcy0_7nMWLK7 umaBek2qJLzF9vdDPtgc df5l6rJbzHKk__BrH8tK _8xnBSvtrAxp8wF9yowC oMWvLBjtaY1vTdo7u3vN JeVit_1klYopnNwu_8oy">
        <div class="pwHzQSmcpCtRGfVoeOdG SZQeSiboYZ5XUz34Uale">
          <button id="actionsDropdownButton" data-dropdown-toggle="actionsDropdown" class="kqgYncRJQ7spwKfig6It neyUwteEn7DOg9pBSJJE _WclR59Ji8jwfmjPtOei jCISvWkW5oStPH6Wrb_H veFXkDzfJN473U3ycrV8 zhRMeqbg7JsftloqW_W6 MxG1ClE4KPrIvlL5_Q5x _A6LflweZRUwrcL6M2Tk g3OYBOqwXUEW4dRGogkH yjGyQxv8jnYk9_MGMqLN PWreZZgitgAm_Nv4Noh9 pxHuWvF853ck68OLN6ef _Qk4_E9_iLqcHsRZZ4ge v4BixjmUnwud_Hihloof qHIOIw8TObHgD3VvKa5x DpMPWwlSESiYA8EE1xKM hover:text-primary-700 m_8FxTcpOfmK___hAaJ6 _FONMPVaCsLFJJGDaaIL _bKyZ1er5YE_NnrwOCm9 __8kBLtrR_iuU2wW25Lp _cpMMPjFQqjJu4i0Puod eCx_6PNzncAD5yo7Qcic _BIVSYBXQUqEf_ltPrSk DTyjKhtXBNaebZa5L0l9 _OovBxfPdK7Rjv2nh2Ot" type="button">
            <svg class="wikskPDYEBn0nlvDss8h rd9r00vboqD3jj2DVT_m eUuXwBkW5W4__eatjSfd RRXFBumaW2SHdseZaWm6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path clip-rule="evenodd" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"></path>
            </svg>
            Actions
          </button>
          <div id="actionsDropdown" class="_v8hjA9ct_v6OhSQD7fC j2x7_17hqRVmwte_tWFa yjGyQxv8jnYk9_MGMqLN FQJBolKGENZMnnBWg95Y _JhddqALGzNXF3JHzSyG YPSoR6AXtPgkmylUmcbT lhxYQ_2y3sYNN3W1V_3q I1YcaBmlNzBwJ5EiwKYF _t2wg7hRcyKsNN8CSSeU WoQqugRcWrYbmsWhxCUr" data-popper-placement="bottom" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(179px, 289px);">
            <ul class="e6xSuaqE4UvMawjVXuq_ MxG1ClE4KPrIvlL5_Q5x jJJfWn8GV_ODBSwRp2nH mEiJmUhVeO0zHYjQchDB" aria-labelledby="actionsDropdownButton">
              <li>
                <a href="#" class="ttxtqsLWp2pFRX8yUvWd veFXkDzfJN473U3ycrV8 zhRMeqbg7JsftloqW_W6 DpMPWwlSESiYA8EE1xKM xotVay0PVtR3gElm6ql5 DTyjKhtXBNaebZa5L0l9">
                  Mass Edit
                </a>
              </li>
            </ul>
            <div class="e6xSuaqE4UvMawjVXuq_">
              <a href="#" class="ttxtqsLWp2pFRX8yUvWd veFXkDzfJN473U3ycrV8 zhRMeqbg7JsftloqW_W6 MxG1ClE4KPrIvlL5_Q5x jJJfWn8GV_ODBSwRp2nH DpMPWwlSESiYA8EE1xKM xotVay0PVtR3gElm6ql5 mEiJmUhVeO0zHYjQchDB DTyjKhtXBNaebZa5L0l9">
                Delete all
              </a>
            </div>
          </div>
        </div>
        <div class="i0EfZzmTLElZVOble53D jCISvWkW5oStPH6Wrb_H _i9FbfrBNYoFTPUHnAds tczQgPamciYPsV_Bd0wD jtAEAmTYcbMMu_bzudpA Nqfh9X2Rexp2qnMJ2IPa">
          <div class="jCISvWkW5oStPH6Wrb_H">
            <label for="brand" class="BWabIWdbZ5qWNbPXxuBc">Brand</label>
            <select id="brand" class="ttxtqsLWp2pFRX8yUvWd _gKcj49wZgnwx1LpcJi6 psGSaoX3vEaTuVjCVZ1M jCISvWkW5oStPH6Wrb_H MxG1ClE4KPrIvlL5_Q5x K1PPCJwslha8GUIvV_Cr bHPiH67mBn1_jgR3TrvW cMZ6g1VlTxVbLLDgApBS DdH0nfuxX7trZkxwQjEs pxHuWvF853ck68OLN6ef rSfGuZzTLmhPzNHMO1jb eCx_6PNzncAD5yo7Qcic JeVit_1klYopnNwu_8oy qHIOIw8TObHgD3VvKa5x W83fbcqTDAidAC5iVTZ9 R9wivzcaVXAMWcTVd6_t peer">
              <option selected="">Brand</option>
              <option value="purple">Samsung</option>
              <option value="primary">Apple</option>
              <option value="pink">Pink</option>
              <option value="green">Green</option>
            </select>
          </div>
          <div class="jCISvWkW5oStPH6Wrb_H">
            <label for="price" class="BWabIWdbZ5qWNbPXxuBc">Price</label>
            <select id="price" class="ttxtqsLWp2pFRX8yUvWd _gKcj49wZgnwx1LpcJi6 psGSaoX3vEaTuVjCVZ1M jCISvWkW5oStPH6Wrb_H MxG1ClE4KPrIvlL5_Q5x K1PPCJwslha8GUIvV_Cr bHPiH67mBn1_jgR3TrvW cMZ6g1VlTxVbLLDgApBS DdH0nfuxX7trZkxwQjEs pxHuWvF853ck68OLN6ef rSfGuZzTLmhPzNHMO1jb eCx_6PNzncAD5yo7Qcic JeVit_1klYopnNwu_8oy qHIOIw8TObHgD3VvKa5x W83fbcqTDAidAC5iVTZ9 R9wivzcaVXAMWcTVd6_t peer">
              <option selected="">Price</option>
              <option value="below-100">$ 1-100</option>
              <option value="below-500">$ 101-500</option>
              <option value="below-1000">$ 501-1000</option>
              <option value="over-1000">$ 1001+</option>
            </select>
          </div>
          <div class="jCISvWkW5oStPH6Wrb_H">
            <label for="category" class="BWabIWdbZ5qWNbPXxuBc">Category</label>
            <select id="category" class="ttxtqsLWp2pFRX8yUvWd _gKcj49wZgnwx1LpcJi6 psGSaoX3vEaTuVjCVZ1M jCISvWkW5oStPH6Wrb_H MxG1ClE4KPrIvlL5_Q5x K1PPCJwslha8GUIvV_Cr bHPiH67mBn1_jgR3TrvW cMZ6g1VlTxVbLLDgApBS DdH0nfuxX7trZkxwQjEs pxHuWvF853ck68OLN6ef rSfGuZzTLmhPzNHMO1jb eCx_6PNzncAD5yo7Qcic JeVit_1klYopnNwu_8oy qHIOIw8TObHgD3VvKa5x W83fbcqTDAidAC5iVTZ9 R9wivzcaVXAMWcTVd6_t peer">
              <option selected="">Category</option>
              <option value="pc">PC</option>
              <option value="phone">Phone</option>
              <option value="tablet">Tablet</option>
              <option value="console">Gaming/Console</option>
            </select>
          </div>
          <div class="jCISvWkW5oStPH6Wrb_H">
            <label for="color" class="BWabIWdbZ5qWNbPXxuBc">Color</label>
            <select id="color" class="ttxtqsLWp2pFRX8yUvWd _gKcj49wZgnwx1LpcJi6 psGSaoX3vEaTuVjCVZ1M jCISvWkW5oStPH6Wrb_H MxG1ClE4KPrIvlL5_Q5x K1PPCJwslha8GUIvV_Cr bHPiH67mBn1_jgR3TrvW cMZ6g1VlTxVbLLDgApBS DdH0nfuxX7trZkxwQjEs pxHuWvF853ck68OLN6ef rSfGuZzTLmhPzNHMO1jb eCx_6PNzncAD5yo7Qcic JeVit_1klYopnNwu_8oy qHIOIw8TObHgD3VvKa5x W83fbcqTDAidAC5iVTZ9 R9wivzcaVXAMWcTVd6_t peer">
              <option selected="">Color</option>
              <option value="purple">Purple</option>
              <option value="primary">primary</option>
              <option value="pink">Pink</option>
              <option value="green">Green</option>
            </select>
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
            const addScheduleValidation = @json(session('modal_open'));
            const selectedPersonnelId = @json(session('selected_personnel'));

            // personnel modal
            const personnelModal = document.getElementById('personnel-table-modal');
            const openPersonnelModalBtn = document.getElementById('open-personnel-modal');
            const closePersonnelModalBtn = document.getElementById('close-personnel-table');
            const searchPersonnel = document.getElementById('search-personnel');
            const personnelTableHead = document.getElementById('personnel-table-head');
            const personnelTableBody = document.getElementById('personnel-table-body');
            const selectBox = document.getElementById('personnel-rule');
            const todayDate = document.getElementById('today-date');

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
                            displaySelectedPersonnel(selectedPersonnel);
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

            // Function to display selected personnel in the main table
            function displaySelectedPersonnel(personnel) {
                const today = new Date();
                const startOfWeek = new Date(today);
                startOfWeek.setDate(today.getDate() - today.getDay() - 1);
                const endOfWeek = new Date(startOfWeek);
                endOfWeek.setDate(startOfWeek.getDate() + 6);

                scheduleTableHead.classList.remove('hidden');

                let rows = `
                    <tr class="bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-b-lg">
                        <td class="px-6 py-4">${personnel.full_name}</td>
                `;

                for (let date = new Date(startOfWeek); date <= endOfWeek; date.setDate(date.getDate() + 1)) {
                    const jalaaliDate = jalaali.toJalaali(date);
                    const gregorianDate = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}`;
                    const calendarEntry = calendars.find(calendar => calendar.date.split(' ')[0] == gregorianDate);
                    const personnelSchedules = calendarEntry ? calendarEntry.schedules.filter(schedule => schedule.personnel_id === personnel.id) : [];

                    if (calendarEntry && calendarEntry.is_holiday) {
                        rows += `
                            <td class="px-6 py-4">
                                <p class="text-red-500 font-bold">تعطیل</p>
                            </td>
                        `;
                    } else if (calendarEntry && !calendarEntry.is_holiday) {
                        if (personnelSchedules.length > 0) {
                            personnelSchedules.forEach(schedule => {
                                console.log(schedule)
                                rows += `<td class="px-6 py-4">
                                    <div id="edit-schedule-modal-${calendarEntry.id}-${personnel.id}" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
                                        <div class="max-w-4xl w-full bg-white p-6 rounded-lg shadow-lg">
                                            <div class="flex justify-between items-center mb-4">
                                                <h2 class="text-lg font-semibold">ویرایش شیفت کاری ${personnel.full_name}</h2>
                                                <button id="close-edit-schedule-modal-${calendarEntry.id}-${personnel.id}" class="text-gray-500 hover:text-gray-700">X</button>
                                            </div>

                                            <div id="edit-schedule-body-${calendarEntry.id}-${personnel.id}" class="mt-5">
                                                <form class="w-full " action="{{ route('schedule.update', '') }}/1" method="POST">
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
                                                            <input type="text" min="0" = name="title_${calendarEntry.id}_${personnel.id}" class="w-full mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
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
                                                                <input type="time" id="from_date" name="from_date_${calendarEntry.id}_${personnel.id}" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                                                            </div>

                                                                <p class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-1">

                                                                </p>

                                                        </div>
                                                        <div class="flex w-full flex-col items-start">
                                                            <label for="to_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">تایم پایان شیفت:*</label>
                                                            <div class="relative w-full">
                                                                <div class="absolute inset-y-0 right-0 top-0 flex items-center pr-3.5 pointer-events-none">
                                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                </div>
                                                                <input type="time" id="to_date" name="to_date_${calendarEntry.id}_${personnel.id}" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
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

                                    <div class="flex justify-center items-center gap-2">
                                        <form action="{{ route('schedule.destroy', '') }}/${schedule.id}" class="flex items-center justify-center" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold trash-toggle">
                                                <x-icons.trash />
                                            </button>
                                        </form>
                                        <button id="open-edit-schedule-modal-${calendarEntry.id}-${personnel.id}" class="text-yellow-500 hover:text-yellow-700 text-sm font-semibold select-personnel"><x-edit-icon /></button>
                                        <form action="{{ route('schedule.copy', '' ) }}/${schedule.id}" class="flex items-center justify-center" method="post">
                                            @csrf
                                            <button type="submit" class="text-green-500 hover:text-green-700 text-sm font-semibold trash-toggle">
                                                <x-icons.copy-icon />
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>`;
                            });

                        } else {
                            rows += `<td class="px-6 py-4">
                                    <div id="add-schedule-modal-${calendarEntry.id}-${personnel.id}" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
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

                                                                <p class="text-sm text-red-600 dark:text-red-400 space-y-1 mt-1">

                                                                </p>

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
                                </div>
                            </td>`;
                        }
                    }

                }

                rows += `</tr>`;

                scheduleTableBody.innerHTML = rows;

                // open button
                document.querySelectorAll("[id^=open-add-schedule-modal-]").forEach(btn => {
                    btn.addEventListener("click", (event) => {
                        const identifier = event.currentTarget.id.replace("open-add-schedule-modal-", "");
                        const addScheduleModal = document.getElementById(`add-schedule-modal-${identifier}`);

                        if (addScheduleModal) {
                            addScheduleModal.classList.remove("hidden");
                            generateServicesSelectBox(personnel, identifier);
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


            // functions
            function getPersianMonthsOfYear(jalaliMonth) {
                const monthsOfYear = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
                return monthsOfYear[jalaliMonth - 1];
            }
            function getPersianDaysOfWeak(jalaliDay) {
                const daysOfWeek = ['یک شنبه', 'دو شنبه', 'سه شنبه', 'چهار شنبه', 'پنج شنبه', 'جمعه', 'شنبه',];
                return daysOfWeek[jalaliDay - 1];
            }

            // service options
            function generateServicesSelectBox (personnel, id) {
                const serviceSelectBox = document.getElementById(`service-${id}`);
                let options = '';

                options += `<option disabled selected value="">یکی از خدمات زیر را انتخاب کنید.</option>`;

                personnel.medicalservices.forEach(service => {
                    options += `<option value="${service.id}">${service.name}</option>`;
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
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jalaali-js/dist/jalaali.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jalaali-js/dist/jalaali.min.js"></script>
    <script src="https://unpkg.com/jalaali-js/dist/jalaali.js"></script>
    <script src="https://unpkg.com/jalaali-js/dist/jalaali.min.js"></script>
</x-app-layout>

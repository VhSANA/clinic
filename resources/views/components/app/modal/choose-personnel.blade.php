@props([
    'button_title',
    'modal_title',
    'model',
    'path',
    'selectedWeek'
])

<x-modal-with-toggle
    button_title="{{ $button_title }}"
    modal_title="{{ $modal_title }}"
    :model="$model"
    path="{{ $path }}"
>
    {{-- search box --}}
    <div class="relative">
        <div class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
        </div>
        <form action="" >
            @csrf
            <input type="text" name="search" value="{{ request('search') }}" id="table-search" class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="جستجو">
        </form>
    </div>

    {{-- personnel table --}}
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-6 py-3">
                نام پرسنل
            </th>
            <th scope="col" class="px-6 py-3">
                کد پرسنلی
            </th>
            <th scope="col" class="px-6 py-3">
                عملیات
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($model as $item)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="flex items-center justify-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <img class="w-10 h-10 rounded-full" src="{{ profileImageFunction($item->user) }}" alt="profile image of {{ $item->full_name }}">
                    <div class="ps-3">
                        <div class="text-base font-semibold">
                            {{ $item->full_name }}
                        </div>
                    </div>
                </th>
                <td class="px-6 py-4">
                    {{ $item->personnel_code }}
                </td>
                <td class="px-6 py-4 text-center flex items-center justify-center">
                    <a href="{{route('personnel.show', $item->id)}}" class="font-medium ml-5 text-green-600 dark:text-blue-500 hover:underline">
                        جزییات پرسنل
                    </a>
                    <form action="{{route('schedule.index')}}" method="get">
                        @csrf
                        <input type="hidden" name="personnel_id" value="{{ $item->id }}">
                        <input type="hidden" name="selectedWeek" value="{{ $selectedWeek }}">
                        <button type="submit" class="font-medium ml-5 text-green-600 dark:text-blue-500 hover:underline">
                            <x-add-icon />
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</x-modal-with-toggle>

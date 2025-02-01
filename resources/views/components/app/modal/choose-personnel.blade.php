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

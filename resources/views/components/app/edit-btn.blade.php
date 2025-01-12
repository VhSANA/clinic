@props([
    'type' => 'a',
    'route'
])

@if ($type == 'a')
    <a href="{{$route}}" class="rounded-full border-2 border-gray-400 dark:border-gray-700 font-semibold text-black dark:text-white px-4 py-2 hover:bg-gray-900 hover:text-white transition flex justify-center items-center">
        <p class="ml-4">{{ $slot }}</p>
        <x-edit-icon />
    </a>
@else
    <button type="submit" class="rounded-full border-2 border-gray-400 dark:border-gray-700 font-semibold text-black dark:text-white px-4 py-2 hover:bg-gray-900 hover:text-white transition flex justify-center items-center">
        <p class="ml-4">{{ $slot }}</p>
        <x-edit-icon />
    </button>
@endif

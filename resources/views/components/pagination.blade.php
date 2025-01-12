@props([
    'model' => $model,
    'path' => $path
])
@if (count($model) != 0)
<nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4 px-2 pb-2" aria-label="Table navigation">
<span class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">نمایش کاربران <span class="font-semibold text-gray-900 dark:text-white">1 الی {{ count($model) >= 10 ? '10' : count($model) }}</span> از <span class="font-semibold text-gray-900 dark:text-white">{{ count($path::all()) }}</span></span>
    {{ $slot }}
</nav>
@endif

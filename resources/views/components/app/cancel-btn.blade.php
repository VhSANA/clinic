@props([
    'route'
])
<a href="{{$route}}"
    class="rounded-full  bg-red-600 dark:bg-red-800 text-white dark:text-white antialiased font-bold hover:bg-red-800 dark:hover:bg-red-900 px-4 py-2 flex items-center justify-between transition">
    <p class="ml-4">{{ $slot }}</p>
    <x-cancel-icon />
</a>

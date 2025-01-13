@props([
    'route',
])
<form :action="$route" method="post">
    @csrf
    @method('DELETE')
    <button class="flex-1 rounded-full bg-red-600 dark:bg-red-800 text-white dark:text-white antialiased font-bold hover:bg-red-800 dark:hover:bg-red-900 px-4 py-2 flex items-center justify-between">
        <p class="ml-4">{{ $slot }}</p>
        <x-delete-icon />
    </button>
</form>

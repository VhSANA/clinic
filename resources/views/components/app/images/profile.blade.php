@props([
    'model'
])
@php
$personnel = new App\Models\Personnel();
    if ($model instanceof $personnel) {
        $model = $model->user;
    }
@endphp
<img class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto mt-4"
        src="{{ profileImageFunction($model) }}" alt="profile picture of {{$model->full_name}}">
<div class="py-2">
    <h3 class="font-bold text-2xl text-gray-800 dark:text-white mb-1">{{ $slot }}</h3>
</div>

@props([
    'name',
    'label',
    'type' => 'text',
    'placeholder'
])

@switch($type)
    @case('select')
        <div class="mt-4">
            <x-input-label for="{{$name}}" class="mb-1" :value="__($label)" />
            <select name="{{$name}}" id="{{$name}}" required class="rounded-lg border-gray-300 w-full placeholder-gray-300 ">
                {{ $slot }}
            </select>
            <x-input-error :messages="$errors->get($name)" class="mt-2" />
        </div>
        @break
    @case('file')
        <div class="mt-4">
            <x-input-label for="{{$name}}" class="mb-3" :value="__($label)" />
            <x-file-input id="{{$name}}" name="{{$name}}" />
            <x-input-error :messages="$errors->get($name)" class="mt-2" />
        </div>
        @break
    @case('textarea')
        <div class="mt-4">
            <x-input-label for="{{$name}}" class="mb-3" :value="__($label)" />
            <textarea id="{{$name}}" name="{{$name}}" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{$placeholder}}"></textarea>
            <x-input-error :messages="$errors->get($name)" class="mt-2" />
        </div>
        @break
    @default
    <div class="mt-4">
        <x-input-label for="{{$name}}" :value="__($label)" />
        <x-text-input id="{{$name}}" required class="block mt-1 w-full placeholder-gray-300" type="{{$type}}" name="{{$name}}" :value="old($name)"  autofocus autocomplete="{{$name}}" placeholder="{{$placeholder}}"/>
        <x-input-error :messages="$errors->get($name)" class="mt-2" />
    </div>
@endswitch

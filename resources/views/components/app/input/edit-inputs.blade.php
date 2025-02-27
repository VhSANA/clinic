@props([
    'name',
    'label',
    'type' => 'text',
    'value',
    'checked',
])

@switch($type)
    @case('textarea')
        <div class="mt-4">
            <x-input-label for="{{$name}}" class="mb-3" :value="__($label)" />
            <textarea id="{{$name}}" name="{{$name}}" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ empty($value) ? 'توضیحی ثبت نشده است' : ''}}" >{{ $value }}</textarea>
            <x-input-error :messages="$errors->get($name)" class="mt-2" />
        </div>
        @break
    @case('checkbox')
        <div class="mt-4">
            <div class="flex">
                <input type="hidden" name="{{$name}}" value="off" />
                <input class="ml-2 rounded-md" id="{{$name}}" type="checkbox" name="{{$name}}" value="on"  autofocus {{ $checked ? 'checked' : '' }} />
                <x-input-label for="{{$name}}" :value="__($label)" />
            </div>
            <x-input-error :messages="$errors->get($name)" class="mt-2" />
        </div>
        @break
    @case('select')
        <div class="mt-4">
            <x-input-label for="{{$name}}" class="mb-1" :value="__($label)" />
            <select name="{{$name}}" id="{{$name}}" class="rounded-lg border-gray-300 w-full placeholder-gray-300 ">
                {{ $slot }}
            </select>
            <x-input-error :messages="$errors->get($name)" class="mt-2" />
        </div>
        @break
    @case('datetime')
        <div class="mt-4">
            <div class="flex flex-col w-full">
                <x-input-label for="{{$name}}" :value="__($label)" />
                <x-app.input.datetime-input id="{{$name}}" name="{{$name}}" value="{{ $value }}" autofocus/>
            </div>
            <x-input-error :messages="$errors->get($name)" class="mt-2" />
        </div>
        @break
    @default
    <div class="mt-4">
        <x-input-label for="{{$name}}" :value="__($label)" />
        <x-text-input id="{{$name}}"  class="block mt-1 w-full placeholder-gray-300" type="{{$type}}" name="{{$name}}" :value="$value" autofocus autocomplete="{{$name}}" />
        <x-input-error :messages="$errors->get($name)" class="mt-2" />
    </div>
@endswitch

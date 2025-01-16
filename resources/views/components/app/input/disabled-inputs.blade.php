@props([
    'name',
    'label',
    'type' => 'text',
    'value',
    'checked',
])

@switch($type)
    @case('select')
        <div class="mt-4">
            <x-input-label for="{{$name}}" class="mt-3" :value="__($label)" />
            <select disabled id="{{$name}}" required class="rounded-lg border-gray-300 w-full placeholder-gray-300 ">
                {{ $slot }}
            </select>
        </div>
        @break
    @case('file')
        <div class="mt-4">
            <x-input-label for="{{$name}}" class="my-3" :value="__($label)" />
            <x-file-input id="{{$name}}" disabled />
        </div>
        @break
    @case('textarea')
        <div class="mt-4">
            <x-input-label for="{{$name}}" class="mb-3" :value="__($label)" />
            <textarea id="{{$name}}" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled  placeholder="{{ empty($value) ? 'توضیحی ثبت نشده است.' : ''}}" >{{$value}}</textarea>
        </div>
        @break
    @case('checkbox')
        <div class="mt-4">
            <div class="flex">
                <input class="ml-2 rounded-md" id="{{$name}}" type="checkbox" name="{{$name}}" :value="$checked ? true : false"  disabled {{ $checked ? 'checked' : '' }} />
                <x-input-label for="{{$name}}" :value="__($label)" />
            </div>
        </div>
        @break
    @default
    <div class="mt-4">
        <x-input-label for="{{$name}}" :value="__($label)" />
        <x-text-input id="{{$name}}" disabled required class="block mt-1 w-full placeholder-gray-300" type="{{$type}}" :value="$value" />
    </div>
@endswitch

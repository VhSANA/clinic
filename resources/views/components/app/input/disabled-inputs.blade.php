@props([
    'name',
    'label',
    'type' => 'text',
    'value'
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
    @default
    <div class="mt-4">
        <x-input-label for="{{$name}}" :value="__($label)" />
        <x-text-input id="{{$name}}" disabled required class="block mt-1 w-full placeholder-gray-300" type="{{$type}}" :value="$value" />
    </div>
@endswitch

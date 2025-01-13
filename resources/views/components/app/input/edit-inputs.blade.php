@props([
    'name',
    'label',
    'type' => 'text',
    'value'
])

<div class="mt-4">
    <x-input-label for="{{$name}}" :value="__($label)" />
    <x-text-input id="{{$name}}" required class="block mt-1 w-full placeholder-gray-300" type="{{$type}}" name="{{$name}}" :value="$value" autofocus autocomplete="{{$name}}" />
    <x-input-error :messages="$errors->get('{{$name}}')" class="mt-2" />
</div>

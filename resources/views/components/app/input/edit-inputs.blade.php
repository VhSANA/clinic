@props([
    'name',
    'label',
    'type' => 'text',
    'value'
])

@switch($type)
    @case('textarea')
        <div class="mt-4">
            <x-input-label for="{{$name}}" class="mb-3" :value="__($label)" />
            <textarea id="{{$name}}" name="{{$name}}" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >{{ $value }}</textarea>
            @error($name)
                <span class="text-red-500 text-sm"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        @break
    @default
    <div class="mt-4">
        <x-input-label for="{{$name}}" :value="__($label)" />
        <x-text-input id="{{$name}}" required class="block mt-1 w-full placeholder-gray-300" type="{{$type}}" name="{{$name}}" :value="$value" autofocus autocomplete="{{$name}}" />
        @error($name)
            <span class="text-red-500 text-sm"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
@endswitch

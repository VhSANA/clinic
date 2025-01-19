@props([
    'cancel_route'
])
<div class="flex justify-evenly mt-3">
    <x-app.button.add-btn >ایجاد</x-app.add-btn>
    <x-app.button.cancel-btn :route="$cancel_route">لغو</x-app.cancel-btn>
</div>

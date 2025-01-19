@props([
    'route'
])
<div class="mt-5 flex gap-2 px-2 justify-around items-center">
    <x-app.button.cancel-btn :route="$route">لغو ویرایش</x-app.cancel-btn>
    <x-app.button.edit-btn type="button">ثبت ویرایش</x-app.edit-btn>
</div>

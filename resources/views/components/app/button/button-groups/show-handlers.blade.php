@props([
    'delete',
    'edit',
    'model',
    'class' => 'flex mt-3 gap-2 px-2 justify-around items-center'
])
<div class="{{ $class }}">
    <x-app.button.delete-btn :route="$delete">حذف</x-app.delete-btn>
    <x-app.button.edit-btn :route="$edit">ویرایش</x-app.edit-btn>
</div>

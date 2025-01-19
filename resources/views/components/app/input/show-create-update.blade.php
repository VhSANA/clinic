@props([
    'label',
    'model'
])
<div class=" row flex items-center justify-center gap-2">
    {{-- تاریخ ایجاد --}}
    <x-app.input.disabled-inputs name="created_at" label="تاریخ ایجاد {{$label}}" :value="$model->created_at == null ? '-' : \Carbon\Carbon::create($model->created_at)->toDayDateTimeString()" />

    <!-- تاریخ ویرایش  -->
    <x-app.input.disabled-inputs name="updated_at" label="تاریخ ویرایش {{$label}}" :value="$model->updated_at == null ? '-' : \Carbon\Carbon::create($model->updated_at)->toDayDateTimeString()" />
</div>

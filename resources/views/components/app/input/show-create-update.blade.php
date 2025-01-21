@props([
    'label',
    'model',
    'class' => 'row flex items-center justify-center gap-2'
])
<div class="{{$class}}">
    {{-- تاریخ ایجاد --}}
    <x-app.input.disabled-inputs name="created_at" label="تاریخ ایجاد {{$label}}" :value="$model->created_at == null ? '-' : \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::create($model->created_at))->format('%A, %d %B %Y')" />

    <!-- تاریخ ویرایش  -->
    @if (($model->created_at == $model->updated_at) || $model->updated_at == null)
        <x-app.input.disabled-inputs name="updated_at" label="تاریخ ویرایش {{$label}}" value="-" />
    @else
        <x-app.input.disabled-inputs name="updated_at" label="تاریخ ویرایش {{$label}}" :value="\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::create($model->updated_at))->format('%A, %d %B %Y')" />
    @endif
</div>

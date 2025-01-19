@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">
    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            @if (Auth::user()->id == $user->id)
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <form action="{{ route('users.update', $user->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="flex flex-col items-center justify-center">
                            {{-- تصویر پروفایل --}}
                            <x-app.images.profile :model="$user">{{ $user->full_name }}</x-app.images.profile>

                            {{-- آپلود تصویر --}}
                            <x-app.input.all-inputs name="image_url" label="آپلود تصویر جدید" type="file" />

                            {{-- نام کامل --}}
                            <x-app.input.edit-inputs name="full_name" label="نام و نام خانوادگی" :value="$user->full_name" />
                        </div>
                        <div class="row flex items-center justify-center gap-2">
                                {{-- نام کاربری --}}
                                <x-app.input.edit-inputs name="username" label="نام کاربری" :value="$user->username" />

                                {{-- سمت کاربر --}}
                                <x-app.input.all-inputs name="rules" label="سمت کاربر" type="select">
                                    @if (count(App\Models\Rule::all()) == 0)
                                        <option value="0">موردی یافت نشد</option>
                                    @else
                                        @foreach (App\Models\Rule::all() as $rule)
                                            <option value="{{ $rule->id }}" {{ in_array($rule->id, $user->rules->pluck('id')->toArray()) ? 'selected' : ''}}>
                                                {{ $rule->persian_title }} - {{ $rule->title }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-app.input.all-inputs>
                            </div>
                        </div>
                        <div class=" row flex items-center justify-center gap-2">
                            <!-- Password -->
                            <x-app.input.edit-inputs name="password" type="password" label="رمزعبور" placeholder="درصورت عدم تغییر خالی بگذارید." value=""/>

                            <!-- Confirm Password -->
                            <x-app.input.edit-inputs name="password_confirmation" type="password" label="تکرار رمزعبور"  value="" placeholder="درصورت عدم تغییر خالی بگذارید" />
                        </div>
                        <div class=" row flex items-center justify-center gap-2">
                            <x-app.input.edit-inputs name="national_code" label="کد ملی کاربر" type="number" :value="$user->national_code" />

                            <x-app.input.edit-inputs name="mobile" label="شماره موبایل کاربر" type="number" :value="$user->mobile" />
                        </div>

                        {{-- تاریخ تولید و ویرایش --}}
                        <x-app.input.show-create-update label="کاربر" :model="$user" />

                        {{-- عملیات ویرایش --}}
                        <x-app.button.button-groups.edit-handlers :route="route('users.show', $user->id)" />
                    </form>
                </div>
            </div>
            @else
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <img class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto my-4"
                        src="https://img.freepik.com/premium-photo/3d-render-man-doctor-avatar-round-sticker-with-cartoon-character-face-user-id-thumbnail-modern_1181551-95.jpg?semt=ais_hybrid" alt="">
                    <div class="py-2">
                        <h2 class="font-bold text-2xl text-gray-800 dark:text-white mb-1">شما مجاز به مشاهده اطلاعات این کاربر نیستید!</h2>
                    </div>
                </div>
            </div>
            @endif
        </div>
    <!-- Card end -->
</div>

@endsection

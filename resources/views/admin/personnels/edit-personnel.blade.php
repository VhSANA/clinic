@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">
    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            @if (Auth::user()->id == $personnel->user->id)
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <form action="{{ route('personnel.update', $personnel->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="flex flex-col items-center justify-center">
                            <div class="flex flex-col items-center justify-center">
                                {{-- تصویر پروفایل --}}
                                <x-app.images.profile :model="$personnel">{{ $personnel->full_name }}</x-app.images.profile>

                                {{-- آپلود تصویر --}}
                                <x-app.input.all-inputs name="image_url" label="آپلود تصویر جدید" type="file" />

                                {{-- نام کامل --}}
                                <x-app.input.edit-inputs name="full_name" label="نام و نام خانوادگی" :value="$personnel->full_name" />
                            </div>
                        </div>
                        <div class=" row flex items-center justify-center gap-2">
                            {{-- نام کاربری --}}
                            <x-app.input.edit-inputs name="username" label="نام کاربری" :value="$personnel->user->username" />

                            {{-- شماره پرسنلی --}}
                            <x-app.input.edit-inputs name="personnel_code" label="شماره پرسنلی" :value="$personnel->personnel_code" />
                        </div>

                        {{-- تاریخ تولید و ویرایش --}}
                        <x-app.input.show-create-update label="پرسنل" :model="$personnel" />

                        {{-- عملیات ویرایش --}}
                        <x-app.button.button-groups.edit-handlers :route="route('personnel.show', $personnel->id)" />
                    </form>
                </div>
            </div>
            @else
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <img class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto my-4"
                        src="https://img.freepik.com/premium-photo/3d-render-man-doctor-avatar-round-sticker-with-cartoon-character-face-personnel-id-thumbnail-modern_1181551-95.jpg?semt=ais_hybrid" alt="">
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

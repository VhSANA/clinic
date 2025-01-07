<x-guest-layout>
    <form method="POST" action="{{ route('personnel.store') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="full_name" :value="__('نام و نام خانوادگی پرسنل')" />
            <x-text-input id="full_name" class="block mt-1 w-full placeholder-gray-300" type="text" name="full_name" :value="old('full_name')"  autofocus autocomplete="full_name" placeholder="سپهر برنا"/>
            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
        </div>

        <!-- username -->
        <div>
            <x-input-label for="username" class="mt-3" :value="__('نام کاربری')" />
            <x-text-input id="username" class="block mt-1 w-full placeholder-gray-300" type="text" name="username" :value="old('username')"  autofocus autocomplete="username" placeholder="sepehrbr"/>
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- کد ملی -->
        <div>
            <x-input-label for="national_code" class="mt-3" :value="__('کد ملی')" />
            <x-text-input id="national_code" class="block mt-1 w-full placeholder-gray-300" type="number" name="national_code" :value="old('national_code')"  autofocus autocomplete="national_code" placeholder="1234567890"/>
            <x-input-error :messages="$errors->get("national_code")" class="mt-2" />
        </div>

        <!-- mobile number -->
        <div>
            <x-input-label for="mobile" class="mt-3" :value="__('شماره موبایل')" />
            <x-text-input id="mobile" class="block mt-1 w-full placeholder-gray-300" type="number" name="mobile" :value="old('mobile')"  autofocus autocomplete="mobile" placeholder="09123456789"/>
            <x-input-error :messages="$errors->get('mobile')" class="mt-2" />
        </div>

        <!-- عنوان پرسنل -->
        <div>
            <x-input-label for="user_title" class="mt-3" :value="__('عنوان پرسنل')" />
            <x-text-input id="user_title" class="block mt-1 w-full placeholder-gray-300" type="text" name="user_title" :value="old('user_title')"  autofocus autocomplete="user_title" placeholder="جناب آقای، سرکار خانم و ..."/>
            <x-input-error :messages="$errors->get('user_title')" class="mt-2" />
        </div>

        <!-- مقام پرسنل -->
        <div>
            <x-input-label for="rules" class="mt-3" :value="__('مقام')" />
            <select name="rules" id="rules" class="rounded-lg border-gray-300 w-full placeholder-gray-300 ">
                @if (count(App\Models\Rule::all()) == 0)
                    <option value="0">موردی یافت نشد</option>
                @else
                    @foreach (App\Models\Rule::all() as $rule)
                        <option value="{{ $rule->id }}">
                            {{ $rule->persian_title }} - {{ $rule->title }}
                        </option>
                    @endforeach
                @endif
            </select>
            <x-input-error :messages="$errors->get('rules')" class="mt-2" />
        </div>

        <!-- جنسیت -->
        <div>
            <x-input-label for="gender" class="mt-3" :value="__('جنسیت')" />
            <select name="gender" id="gender" class="rounded-lg border-gray-300 w-full placeholder-gray-300 ">
                <option value="male">مذکر</option>
                <option value="female">مونث</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('رمز عبور')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                             autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('تکرار رمز عبور')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation"  autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('fa_IR');

        $userTitles = ['جناب آقای ', 'سرکار خانم '];
        $userTitleInt = array_rand($userTitles);
        $title = $userTitles[$userTitleInt];

        $gender = ['male', 'female'];
        $genderInt = array_rand($gender);

        return [
            'full_name' => "$faker->firstName $faker->lastName",
            'username' => $faker->userName,
            'national_code' => $faker->nationalCode(),
            'mobile' => '09' . random_int(100000000, 999999999),
            'user_title' => $title,
            'gender' => function () use ($title) {
                switch ($title) {
                    case 'جناب آقای ':
                        return 'male';
                    case 'سرکار خانم ':
                        return 'female';
                }
            },
            'password' => Hash::make('password')
        ];
    }
}

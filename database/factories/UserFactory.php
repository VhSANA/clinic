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

        $jobTitles = ['پزشک','بیمار','منشی','صندوق دار','دستیار پزشک'];
        $jobInt = array_rand($jobTitles);

        $degrees = ['پزشکی','لیاسنس پرستاری','لیسانس حسابداری','لیاسنس','فوق لیسانس'];
        $degreesInt = array_rand($degrees);

        $gender = ['male', 'female'];
        $genderInt = array_rand($gender);

        return [
            'code' => Str::random(10),
            'full_name' => "$faker->firstName $faker->lastName",
            'father' => $faker->firstName,
            'username' => $faker->userName,
            'id_number' => $faker->nationalCode(),
            'mobile' => '09' . random_int(100000000, 999999999),
            'home_number' => random_int(10000000, 99999999),
            'relative_number' => '09' . random_int(100000000, 999999999),
            'relative_name' => $faker->name,
            'user_title' => $jobTitles[$jobInt],
            'degree' => $degrees[$degreesInt],
            'upload_degree' => 'localstorage://' . Str::random(),
            'gender' => $gender[$genderInt],
            'password' => Hash::make('password')
        ];
    }
}

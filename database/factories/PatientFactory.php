<?php

namespace Database\Factories;

use App\Models\Insurance;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('fa_IR');

        $cities = ['تبریز', 'ارومیه', 'تهران', 'زنجان',];
        $citiesInt = array_rand($cities);

        $gender = ['male', 'female'];
        $genderInt = array_rand($gender);

        $relation = ['married', 'single'];
        $relationInt = array_rand($relation);

        $insurances = Insurance::all()->pluck('id')->toArray();
        $insruance = array_rand($insurances);

        $name = $faker->firstName;
        $family = $faker->lastName;
        return [
            'name' => $name,
            'family' => $family,
            'full_name' => "$name $family",
            'father_name' => $faker->firstNameMale,
            'national_code' => $faker->nationalCode(),
            'is_foreigner' => false,
            'passport_code' => null,
            'mobile' => '09' . random_int(100000000, 999999999),
            'phone' => '0' . random_int(100000000, 999999999),
            'address' => $cities[$citiesInt],
            'birth_date' => $faker->date(),
            'gender' => $gender[$genderInt],
            'relation_status' => $relation[$relationInt],
            'insurance_id' => $insurances[$insruance],
            'insurance_number' => random_int(100000, 999999),
        ];
    }
}

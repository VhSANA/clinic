<?php

namespace Database\Factories;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('fa_IR');

        $roomTitle = ['عمل', 'ویزیت', 'اکو و تست ورزشی'];
        $roomTitleInt = array_rand($roomTitle);

        $personnelCapacity = random_int(0,2);

        return [
            'title' => $roomTitle[$roomTitleInt],
            'personnel_capacity' => $personnelCapacity,
            'patient_capacity' => $personnelCapacity * 2
        ];
    }
}

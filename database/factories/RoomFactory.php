<?php

namespace Database\Factories;

use App\Models\Room;
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

    /**
     * Create entries for each room type.
     *
     * @return void
     */
    public static function createAll()
    {
        $roomTitles = ['عمل', 'ویزیت', 'اکو و تست ورزشی', 'لیزر', 'ویزیت بیمار خارجی', ];

        $personnelCapacity = random_int(0,2);

        foreach ($roomTitles as $title) {
            Room::factory()->create([
                'title' => "بیمه " . $title,
                'personnel_capacity' => $personnelCapacity,
                'patient_capacity' => $personnelCapacity * 2
            ]);
        }
    }
}

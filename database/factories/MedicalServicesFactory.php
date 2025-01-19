<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalServices>
 */
class MedicalServicesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('fa_IR');

        $services = ['ویزیت','معاینه','زیبایی','لاغری','زالو',];
        $int = array_rand($services);

        return [
            'name' => $services[$int],
            'description' => $faker->realText(),
            'display_in_list' => random_int(0, 1),
        ];
    }
}

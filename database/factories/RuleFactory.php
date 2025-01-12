<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rule>
 */
class RuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('fa_IR');;

        return [
            'title' => $faker->jobTitle(),
            'persian_title' => $faker->jobTitle(),
            'description' => $faker->realText(),
            'rule_icon' => $faker->imageUrl()
        ];
    }
}

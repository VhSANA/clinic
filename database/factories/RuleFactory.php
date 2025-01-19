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

        $rule = ['doctor','secretary','cashier',];
        $int = array_rand($rule);
        $title = $rule[$int];

        return [
            'title' => $title,
            'persian_title' => function () use ($title) {
                switch ($title) {
                    case 'doctor':
                        return 'پزشک';
                    case 'secretary':
                        return 'منشی';

                    case 'cashier':
                        return 'صندوقدار';
                }
            },
            'description' => $faker->realText(),
            'rule_icon' => null
        ];
    }
}

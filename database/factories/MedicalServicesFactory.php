<?php

namespace Database\Factories;

use App\Models\MedicalServices;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalServices>
 */
class MedicalServicesFactory extends Factory
{
        /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MedicalServices::class;

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

    /**
     * Create entries for each insurance type.
     *
     * @return void
     */
    public static function createAll()
    {
        $services = ['ویزیت','معاینه','زیبایی','لاغری','زالو',];

        foreach ($services as $service) {
            MedicalServices::factory()->create([
                'name' => "بیمه " . $service,
                'description' => FakerFactory::create('fa_IR')->realText,
                'display_in_list' => random_int(0, 1),
            ]);
        }
    }

    /**
     * Create dummy data in related tables.
     *
     * @return void
     */
    public static function createWithRelations()
    {
        $services = MedicalServices::inRandomOrder()->take(6)->get();

        foreach ($services as $service) {
            $personnel = Personnel::inRandomOrder()->first();
            $personnel->medicalservices()->attach($service->id, [
                'estimated_service_time' => fake()->numberBetween(5, 45),
                'service_price' => fake()->numberBetween(10, 500) * 1000,
            ]);
        }
    }
}

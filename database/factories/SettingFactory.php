<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'DUREE_EMPRUNT_MAX' => $this->faker->numberBetween(5, 15),
            'NOMBRE_EMPRUNTS_MAX' => $this->faker->numberBetween(1, 5),
            'DUREE_RESERVATION' => $this->faker->numberBetween(1, 7),
        ];
    }
}

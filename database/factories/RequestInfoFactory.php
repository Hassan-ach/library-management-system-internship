<?php

namespace Database\Factories;

use App\Enums\RequestStatus;
use App\Models\BookRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RequestInfo>
 */
class RequestInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'request_id' => BookRequest::factory(),
            'status' => $this->faker->randomElement([
                RequestStatus::PENDING,
                RequestStatus::APPROVED,
                RequestStatus::CANCELED,
            ]),
            'created_at' => now(),

            //
        ];
    }

    public function pending()
    {
        return $this->state(fn () => [
            'status' => RequestStatus::PENDING,
        ]);
    }
}

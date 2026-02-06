<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Order::class;

    public function definition(): array
    {
        return [
            //
            'user_id' => User::factory(),
            'total' => $this->faker->randomFloat(2, 50, 500),
            'payment_method' => 'cash',
            'recipient_name' => fake()->name(),
            'recipient_email' => fake()->unique()->safeEmail(),
            'recipient_phone' => fake()->phoneNumber(),
            'recipient_address' => fake()->address(),
            

        ];
    }
}

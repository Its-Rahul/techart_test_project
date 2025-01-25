<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'Pending',  // Default status
            'priority' => $this->faker->randomElement(['Low', 'Medium', 'High']),
            'user_id' => User::factory(),  // Automatically creates a user
        ];
    }
}

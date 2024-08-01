<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $roles = [1, 2, 3, 4]; // Define the roles you want to assign

        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->numerify('0###########'), // Generates a 11-digit phone number
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // Default password
            'role' => $this->faker->randomElement($roles), // Randomly assign one of the roles
            'status' => 1,
        ];
    }
}

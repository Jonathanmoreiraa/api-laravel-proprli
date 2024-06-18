<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Building>
 */
class BuildingFactory extends Factory
{
    public function definition(): array
    {
        $userTypes = UserTypes::where('name', 'ilike','owner')->first();
        $user = User::where('user_type_id', $userTypes->id)->inRandomOrder()->first();
        return [
            'name' => fake()->name(),
            'address' => fake()->address(),
            'user_id' => $user->id
        ];
    }
}

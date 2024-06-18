<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\TasksStatuses;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tasks>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->title(),
            'description' => fake()->text(),
            'status_id' => TasksStatuses::all()->random()->id,
            'building_id' => Building::all()->random()->id,
            'creator_id' => User::where('user_type_id', 1)->inRandomOrder()->first()->id,
            'assignee_id' => User::where('user_type_id', 2)->inRandomOrder()->first()->id,
        ];
    }
}

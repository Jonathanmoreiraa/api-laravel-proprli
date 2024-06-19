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
        $building = Building::inRandomOrder()->first();
        return [
            'title' => fake()->title(),
            'description' => fake()->text(),
            'status_id' => TasksStatuses::where('id', 1)->first()->id,
            'building_id' => $building->id,
            'creator_id' =>  $building->user_id,
            'assignee_id' => User::where('user_type_id', 2)->inRandomOrder()->first()->id,
        ];
    }
}

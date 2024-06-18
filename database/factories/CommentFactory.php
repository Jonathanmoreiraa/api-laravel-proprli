<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'comment' => fake()->text(),
            'date' => fake()->dateTime(),
            'task_id' => Task::all()->random()->id,
            'user_id' => User::all()->random()->id
        ];
    }
}

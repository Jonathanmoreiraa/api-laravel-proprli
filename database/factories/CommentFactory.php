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
        $task = Task::inRandomOrder()->first();
        return [
            'comment' => fake()->text(),
            'date' => fake()->dateTime(),
            'task_id' => $task->id,
            'user_id' => $task->creator_id
        ];
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* 
        * Create the initials values that will make the test faster
        */
        \App\Models\UserTypes::factory()->create(['name' => 'Owner']);
        \App\Models\UserTypes::factory()->create(['name' => 'Team Member']);
        \App\Models\TasksStatuses::factory()->create(['name' => 'Open']);
        \App\Models\TasksStatuses::factory()->create(['name' => 'In Progress']);
        \App\Models\TasksStatuses::factory()->create(['name' => 'Completed']);
        \App\Models\TasksStatuses::factory()->create(['name' => 'Rejected']);
        \App\Models\User::factory(5)->create();
        \App\Models\Building::factory(5)->create();
    }
}

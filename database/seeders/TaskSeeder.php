<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Crée un utilisateur admin avec quelques tâches
        $admin = User::factory()->create([
            'name'  => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        Task::factory(5)->create(['user_id' => $admin->id]);

        // Crée d'autres utilisateurs avec leurs tâches
        User::factory(3)->create()->each(function ($user) {
            Task::factory(rand(3, 7))->create(['user_id' => $user->id]);
        });
    }
}

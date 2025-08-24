<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Collaborator;
use App\Models\Task;
use App\Models\TimeTracker;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar colaboradores
        $collaborator1 = Collaborator::create([
            'username' => 'dev1',
            'password' => Hash::make('password123'),
            'name' => 'João Silva',
            'email' => 'joao@example.com'
        ]);

        $collaborator2 = Collaborator::create([
            'username' => 'dev2',
            'password' => Hash::make('password123'),
            'name' => 'Maria Santos',
            'email' => 'maria@example.com'
        ]);

        // Criar projetos
        $project1 = Project::create([
            'name' => 'Sistema de E-commerce',
            'description' => 'Desenvolvimento de uma plataforma completa de e-commerce'
        ]);

        $project2 = Project::create([
            'name' => 'App Mobile',
            'description' => 'Aplicativo mobile para iOS e Android'
        ]);

        // Criar tarefas
        $task1 = Task::create([
            'name' => 'Criar banco de dados',
            'description' => 'Modelagem e criação das tabelas do banco',
            'project_id' => $project1->id,
            'collaborator_id' => $collaborator1->id,
            'status' => 'completed'
        ]);

        $task2 = Task::create([
            'name' => 'Desenvolver API',
            'description' => 'Criar endpoints da API REST',
            'project_id' => $project1->id,
            'collaborator_id' => $collaborator2->id,
            'status' => 'in_progress'
        ]);

        $task3 = Task::create([
            'name' => 'Interface do usuário',
            'description' => 'Desenvolver frontend responsivo',
            'project_id' => $project2->id,
            'collaborator_id' => $collaborator1->id,
            'status' => 'pending'
        ]);

        // Criar time trackers
        TimeTracker::create([
            'task_id' => $task1->id,
            'collaborator_id' => $collaborator1->id,
            'start_time' => now()->subDays(2)->setTime(9, 0),
            'end_time' => now()->subDays(2)->setTime(17, 0)
        ]);

        TimeTracker::create([
            'task_id' => $task2->id,
            'collaborator_id' => $collaborator2->id,
            'start_time' => now()->subDay()->setTime(8, 30),
            'end_time' => now()->subDay()->setTime(16, 30)
        ]);

        TimeTracker::create([
            'task_id' => $task2->id,
            'collaborator_id' => $collaborator2->id,
            'start_time' => now()->setTime(9, 0),
            'end_time' => null // Time tracker ativo
        ]);
    }
}

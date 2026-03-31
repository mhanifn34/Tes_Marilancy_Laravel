<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Job;
use App\Models\Application;
use App\Models\Task;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 1 Admin
        User::create([
            'name' => 'Admin MariLancy',
            'email' => 'admin@marilancy.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Buat 2 Klien
        $client1 = User::create([
            'name' => 'Client Alpha',
            'email' => 'client1@marilancy.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        $client2 = User::create([
            'name' => 'Client Beta',
            'email' => 'client2@marilancy.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        // Buat 5 Freelancer
        $freelancers = [];
        for ($i = 1; $i <= 5; $i++) {
            $freelancers[] = User::create([
                'name' => 'Freelancer ' . $i,
                'email' => 'freelancer' . $i . '@marilancy.com',
                'password' => Hash::make('password'),
                'role' => 'freelancer',
            ]);
        }

        // Buat Jobs
        $job1 = Job::create([
            'client_id' => $client1->id,
            'title' => 'Website E-commerce',
            'description' => 'Buat website toko online dengan Laravel.',
            'category' => 'Web Development',
            'budget' => 5000000,
            'status' => 'open',
        ]);

        $job2 = Job::create([
            'client_id' => $client1->id,
            'title' => 'Logo Design',
            'description' => 'Desain logo untuk startup.',
            'category' => 'Design',
            'budget' => 1000000,
            'status' => 'on_progress',
        ]);

        $job3 = Job::create([
            'client_id' => $client2->id,
            'title' => 'Mobile App',
            'description' => 'Aplikasi mobile untuk tracking fitness.',
            'category' => 'Mobile Development',
            'budget' => 3000000,
            'status' => 'closed',
        ]);

        // Applications
        Application::create([
            'job_id' => $job1->id,
            'freelancer_id' => $freelancers[0]->id,
            'bid_amount' => 4500000,
            'proposal' => 'Saya bisa buat website e-commerce dengan fitur lengkap.',
            'status' => 'pending',
        ]);

        Application::create([
            'job_id' => $job2->id,
            'freelancer_id' => $freelancers[1]->id,
            'bid_amount' => 900000,
            'proposal' => 'Logo design dengan style modern.',
            'status' => 'accepted',
        ]);

        // Tasks untuk job on_progress
        Task::create([
            'job_id' => $job2->id,
            'task_name' => 'Konsep Logo',
            'status' => 'done',
        ]);

        Task::create([
            'job_id' => $job2->id,
            'task_name' => 'Revisi Logo',
            'status' => 'doing',
        ]);

        // Transactions
        Transaction::create([
            'user_id' => $client1->id,
            'job_id' => $job2->id,
            'amount' => 900000,
            'type' => 'expense',
        ]);

        Transaction::create([
            'user_id' => $freelancers[1]->id,
            'job_id' => $job2->id,
            'amount' => 900000,
            'type' => 'income',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Sarah Johnson', 'email' => 'sarah@example.com', 'role' => 'Project Manager'],
            ['name' => 'Mike Chen', 'email' => 'mike@example.com', 'role' => 'Frontend Developer'],
            ['name' => 'Emily Davis', 'email' => 'emily@example.com', 'role' => 'UI/UX Designer'],
            ['name' => 'John Smith', 'email' => 'john@example.com', 'role' => 'Backend Developer'],
            ['name' => 'Lisa Anderson', 'email' => 'lisa@example.com', 'role' => 'Marketing Manager'],
        ];

        foreach ($users as $u) {
            User::create([
                'name' => $u['name'],
                'email' => $u['email'],
                'password' => Hash::make('password'),
                'role' => $u['role']
            ]);
        }
    }
}

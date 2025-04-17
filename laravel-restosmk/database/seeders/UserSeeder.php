<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('123'),
                'level' => 'admin',
            ],
            [
                'name' => 'kasir',
                'email' => 'kasir@example.com',
                'password' => bcrypt('password'),
                'level' => 'kasir',
            ],
            [
                'name' => 'manager',
                'email' => 'manager@example.com',
                'password' => bcrypt('password'),
                'level' => 'manager',
            ],
        ];

        foreach ($users as $value) {
            User::create($value);
        }
    }
}

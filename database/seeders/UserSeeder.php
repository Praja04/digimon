<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Analis QC',
                'email' => 'analis@example.com',
                'role' => 'analis',
            ],
            [
                'name' => 'Foreman QC',
                'email' => 'foreman@example.com',
                'role' => 'foreman',
            ],
            [
                'name' => 'Supervisor QC',
                'email' => 'supervisor@example.com',
                'role' => 'supervisor',
            ],
            [
                'name' => 'Dept Head QC',
                'email' => 'depthead@example.com',
                'role' => 'dept_head',
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'password' => Hash::make('password'), // default password
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

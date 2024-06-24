<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'renter',
                'biography' => 'A brief bio about John Doe.',
                'career' => 'Software Developer',
                'address' => '123 Main St, Anytown, USA',
                'phone_number' => '123-456-7890',
                'verified_member' => true,
                'profile_pic' => 'profile_pics/johndoe.jpg',
                'age' => 30,
                'income' => 60000.00,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'janesmith@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'landlord',
                'biography' => 'A brief bio about Jane Smith.',
                'career' => 'Real Estate Agent',
                'address' => '456 Elm St, Anytown, USA',
                'phone_number' => '987-654-3210',
                'verified_member' => true,
                'profile_pic' => 'profile_pics/janesmith.jpg',
                'age' => 28,
                'income' => 75000.00,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jony Bebeh',
                'email' => 'jony@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'renter',
                'biography' => 'A brief bio about Jane Smith.',
                'career' => 'Real Estate Agent',
                'address' => '456 Elm St, Anytown, USA',
                'phone_number' => '987-654-3210',
                'verified_member' => true,
                'profile_pic' => 'profile_pics/janesmith.jpg',
                'age' => 28,
                'income' => 75000.00,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jade',
                'email' => 'jadeh@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'landlord',
                'biography' => 'A brief bio about Jane Smith.',
                'career' => 'Real Estate Agent',
                'address' => '456 Elm St, Anytown, USA',
                'phone_number' => '987-654-3210',
                'verified_member' => true,
                'profile_pic' => 'profile_pics/janesmith.jpg',
                'age' => 28,
                'income' => 75000.00,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

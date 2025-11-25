<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@aipropmatch.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'), // change this
            ]
        );
    }
}

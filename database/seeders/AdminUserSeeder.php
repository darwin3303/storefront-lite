<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // unique identifier
            [
                'name' => 'Admin',
                'usertype' => 'admin',
                'phone' => 766474615,
                'address' => 'embilipitiya',
                'password' => Hash::make('123456789')
            ]
        );
    }
}

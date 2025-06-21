<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@aiqfome.com'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('senhaSegura123'),
            ]
        );
    }
}

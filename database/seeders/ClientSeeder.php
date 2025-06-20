<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 10) as $i) {
            Client::create([
                'name'     => "Cliente {$i}",
                'email'    => "cliente{$i}@teste.com",
                'password' => Hash::make('senha123'),
            ]);
        }
    }
}

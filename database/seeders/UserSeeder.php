<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['id' => '00000000-0000-0000-0000-000000000001'],
            [
                'name'     => 'Usuario Demo',
                'email'    => 'demo@tasks-app.local',
                'password' => Hash::make('Demo@2025!'),
            ]
        );
    }
}

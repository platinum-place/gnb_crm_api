<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin user
        \App\Models\Users\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gnb.com',
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin user
        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gnb.com',
        ]);
    }
}

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

        // colonial user
        \App\Models\Users\User::factory()->create([
            'name' => 'admin',
            'email' => 'colonial@gnb.com',
            'account_name_id' => '3222373000013452591',
            'account_name' => 'LA COLONIAL, S.A',
            'contact_name_id' => '3222373000031472977',
        ]);
    }
}

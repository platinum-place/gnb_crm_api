<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeader extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // admin user
        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gnb.com',
        ]);

        // colonial user
        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'colonial@gnb.com',
            'account_name_id' => '3222373000013452591',
            'account_name' => 'LA COLONIAL, S.A',
        ]);
    }
}

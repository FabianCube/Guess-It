<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nickname' => 'Davinci69',
            'email' => 'davinci@demo.com',
            'password' => bcrypt('12345678'),
            'avatar_id' => '1',
            'admin_rights' => '0'
        ]);

        User::create([
            'nickname' => 'SuperDrawer2000',
            'email' => 'superdrawer2000@demo.com',
            'password' => bcrypt('12345678'),
            'avatar_id' => '2',
            'admin_rights' => '0'
        ]);

        User::create([
            'nickname' => 'admin',
            'email' => 'admin@demo.com',
            'password' => bcrypt('12345678'),
            'avatar_id' => '3',
            'admin_rights' => '1'
        ]);
    }
}

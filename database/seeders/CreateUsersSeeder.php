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
            'name' => 'Davinci69',
            'email' => 'davinci@demo.com',
            'password' => bcrypt('12345678'),
            'avatar_id' => '1'
        ]);

        User::create([
            'name' => 'SuperDrawer2000',
            'email' => 'superdrawer2000@demo.com',
            'password' => bcrypt('12345678'),
            'avatar_id' => '2'
        ]);
    }
}

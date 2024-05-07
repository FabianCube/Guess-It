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
            'nickname' => 'Pablo',
            'email' => 'pablo@demo.com',
            'password' => bcrypt('12345678'),
            'avatar_id' => '3',
            'admin_rights' => '0'
        ]);

        User::create([
            'nickname' => 'Juana',
            'email' => 'juana@demo.com',
            'password' => bcrypt('12345678'),
            'avatar_id' => '2',
            'admin_rights' => '0'
        ]);

        User::create([
            'nickname' => 'MegaPencil',
            'email' => 'megapencil@demo.com',
            'password' => bcrypt('12345678'),
            'avatar_id' => '2',
            'admin_rights' => '0'
        ]);

        User::create([
            'nickname' => 'LOL22',
            'email' => 'lol22@demo.com',
            'password' => bcrypt('12345678'),
            'avatar_id' => '2',
            'admin_rights' => '0'
        ]);

        User::create([
            'nickname' => 'OOOP01',
            'email' => 'ooops01@demo.com',
            'password' => bcrypt('12345678'),
            'avatar_id' => '2',
            'admin_rights' => '0'
        ]);
        User::create([
            'nickname' => 'OOOP02',
            'email' => 'ooops02@demo.com',
            'password' => bcrypt('12345678'),
            'avatar_id' => '2',
            'admin_rights' => '0'
        ]);
        User::create([
            'nickname' => 'OOOP03',
            'email' => 'ooops03@demo.com',
            'password' => bcrypt('12345678'),
            'avatar_id' => '2',
            'admin_rights' => '0'
        ]);
        User::create([
            'nickname' => 'OOOP04',
            'email' => 'ooops04@demo.com',
            'password' => bcrypt('12345678'),
            'avatar_id' => '2',
            'admin_rights' => '0'
        ]);
        User::create([
            'nickname' => 'OOOP05',
            'email' => 'ooops05@demo.com',
            'password' => bcrypt('12345678'),
            'avatar_id' => '2',
            'admin_rights' => '0'
        ]);
        User::create([
            'nickname' => 'OOOP06',
            'email' => 'ooops06@demo.com',
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

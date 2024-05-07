<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionTableSeeder::class);
        $this->call(WordsTableSeeder::class);
        $this->call(AvatarTableSeeder::class);
        $this->call(CreateUsersSeeder::class);

//        $this->call(RoleSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'nickname' => 'Test user',
        //     'email' => 'test@demo.com',
        //     'password' => bcrypt('12345678'),
        //     'avatar_id' => '2',
        //     'admin_rights' => '0'
        // ]);
    }
}

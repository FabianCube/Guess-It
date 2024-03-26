<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Avatar;

class AvatarTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            'avatar1.jpg',
            'avatar2.jpg',
            'avatar3.jpg',
            'avatar4.jpg'
        ];

        foreach ($images as $avatar) {
            Avatar::create(['image' => $avatar]);
        }
    }
}

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
        $pepe = [
            'belbel',
            'sdfadf',
            'sdfdsf'
        ];

        foreach ($pepe as $avatar) {
            Avatar::create(['image' => $avatar]);
        }
    }
}

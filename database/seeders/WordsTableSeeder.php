<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Word;

class WordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $easy = [
        'Perro',
        'Casa',
        'Sol',
        'Árbol',
        'Manzana',
        'Flor',
        'Gato',
        'Coche',
        'Pelota',
        'Mesa',
        'Luna',
        'Pato',
        'Silla',
        'Mariposa',
        'Nube',
        'Avión',
        'Pescado',
        'Tren',
        'Globo',
        'Camión',
        'Rana',
        'Corazón',
        'Reloj',
        'Círculo',
        'Estrella',
        'Zapato',
        'Sombrero',
        'Lápiz',
        'Taza',
        'Pizza'];

        $medium = [];

        $hard = [];

        foreach ($easy as $word) {
            Word::create(['name' => $word, 'difficulty' => 'easy']);
        }
    }
}

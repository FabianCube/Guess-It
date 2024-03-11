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
            'Pizza'
        ];

        $medium = [
            'Computadora',
            'Guitarra',
            'Elefante',
            'Jirafa',
            'Avión',
            'Helado',
            'Dinosaurio',
            'Fútbol',
            'Camiseta',
            'Mapa',
            'Teléfono',
            'Pintura',
            'Helicóptero',
            'Reloj',
            'Globo terráqueo',
            'Tortuga',
            'Lápiz',
            'Casa',
            'Ojo',
            'Llave',
            'Pizarra',
            'León',
            'Escalera',
            'Camión',
            'Payaso',
            'Manzana',
            'Calendario',
            'Globo aerostático',
            'Robot',
            'Paloma'
        ];

        $hard = [
            'Telescopio',
            'Micrófono',
            'Elefante africano',
            'Engranaje',
            'Helicóptero de combate',
            'Catarata',
            'Quiosco',
            'Mariposa monarca',
            'Hidroavión',
            'Escarabajo rinoceronte',
            'Quetzal',
            'Crucigrama',
            'Orangután',
            'Péndulo',
            'Paralelepípedo',
            'Triceratops',
            'Circunferencia',
            'Arrecife de coral',
            'Girasol',
            'Catedral',
            'Eclipse solar',
            'Ventilador de techo',
            'Escarabajo pelotero',
            'Locomotora a vapor',
            'Espectrograma',
            'Pentágono',
            'Magnolia',
            'Aloe vera',
            'Esquí acuático',
            'Croissant',
        ];

        foreach ($easy as $word) {
            Word::create(['word' => $word, 'difficulty' => 'easy']);
        }

        foreach ($medium as $word) {
            Word::create(['word' => $word, 'difficulty' => 'medium']);
        }
        
        foreach ($hard as $word) {
            Word::create(['word' => $word, 'difficulty' => 'hard']);
        }
    }
}

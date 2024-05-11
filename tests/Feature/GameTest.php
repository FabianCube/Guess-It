<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameTest extends TestCase
{
    public function test_game_response(): void
    {
        $response = $this->get('/start-game/AAAAA');
        $response->assertStatus(200);

        $response = $this->get('/redirect-game');
        $response->assertStatus(200);

        $response = $this->get('/round-finished');
        $response->assertStatus(200);

        $response = $this->get('/drawer-points');
        $response->assertStatus(200);

        $response = $this->get('/start-timer');
        $response->assertStatus(200);
    }

    public function test_word_game_response(): void
    {
        $category = [
            'easy',
            'medium',
            'hard' 
        ];

        for($i = 0; $i < 2; $i++)
        {
            $response = $this->get('/get-word' . $category[$i]);
            $response->assertStatus(200);
        }
        
        $response = $this->get('/word');
        $response->assertStatus(200);

        $response = $this->get('/correct-word');
        $response->assertStatus(200);

        $response = $this->get('/encrypted-word');
        $response->assertStatus(200);
    }
}

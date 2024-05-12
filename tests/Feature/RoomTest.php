<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomTest extends TestCase
{
    public function test_cache_response(): void
    {
        $response = $this->get('/create-cache');
        $response->assertStatus(200);

        $response = $this->get('/delete-cache');
        $response->assertStatus(200);
    }

    public function test_room_response(): void
    {
        $roomCode = "3LF9YU";

        $response = $this->get('/enter-room');
        $response->assertStatus(200);

        $response = $this->get('/find-room/' . $roomCode);
        $response->assertStatus(200);

        $response = $this->get('/leave-room/' . $roomCode);
        $response->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChatTest extends TestCase
{
    public function test_messages_response(): void
    {
        $response = $this->get('/messages');
        $response->assertStatus(200);
    }

    public function test_canvas_response(): void
    {
        $response = $this->get('/canvas');
        $response->assertStatus(200);

        $response = $this->get('/clean-canvas');
        $response->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_account_response(): void
    {
        $user_id = '1';

        $response = $this->get('/admin-history');
        $response->assertStatus(200);

        $response = $this->get('/admin-players');
        $response->assertStatus(200);

        $response = $this->get('/account-history/' . $user_id);
        $response->assertStatus(200);

        $response = $this->get('/update-user/' . $user_id);
        $response->assertStatus(200);

        $response = $this->get('/delete-user/' . $user_id);
        $response->assertStatus(200);
    }
}

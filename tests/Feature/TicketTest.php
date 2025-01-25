<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
class TicketTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_ticket()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/tickets', [
            'title' => 'Login Issue',
            'description' => 'Unable to log into my account.',
            'priority' => 'High',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'ticket']);

        $this->assertDatabaseHas('tickets', [
            'title' => 'Login Issue',
            'priority' => 'High',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_create_ticket()
    {
        $response = $this->postJson('/api/tickets', [
            'title' => 'Login Issue',
            'description' => 'Unable to log into my account.',
            'priority' => 'High',
        ]);

        $response->assertStatus(401);
    }
}


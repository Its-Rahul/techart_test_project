<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;



class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function agent_can_reply_to_a_ticket()
    {
        // Create an agent user
        $agent = User::factory()->create(['role' => 'agent']);

        // Create a regular user and a ticket
        $user = User::factory()->create(['role' => 'user']);
        $ticket = Ticket::factory()->create(['user_id' => $user->id]);

        // Agent replies to the ticket
        $response = $this->actingAs($agent)->postJson("/api/tickets/{$ticket->id}/reply", [
            'message' => 'We are working on your issue.',
            "status" => "In Progress",

        ]);

        // Assert the response
        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'reply']);

        // Assert the database has the reply
        $this->assertDatabaseHas('replies', [
            'ticket_id' => $ticket->id,
            'message' => 'We are working on your issue.',
            'agent_id' => $agent->id,
        ]);
    }

    /** @test */
    public function non_agent_cannot_reply_to_a_ticket()
    {
        // Create a regular user and a ticket
        $user = User::factory()->create(['role' => 'user']);
        $ticket = Ticket::factory()->create(['user_id' => $user->id]);

        // Regular user attempts to reply to the ticket
        $response = $this->actingAs($user)->postJson("/api/tickets/{$ticket->id}/reply", [
            'message' => 'This should not work.',
        ]);

        // Assert forbidden status
        $response->assertStatus(403); // Forbidden
    }

}

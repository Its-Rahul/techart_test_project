<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ResponseController as ResponseController;


class AdminController extends ResponseController
{
    // Create a new agent
    public function createAgent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $agent = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'agent', // Agent role
        ]);

        return $this->sendResponse($agent, 'Agent created successfully');
    }

    public function getAllTickets(Request $request)
    {
        $query = Ticket::with('user');

        // Check if a priority filter is applied
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->paginate(10);
        return $this->sendResponse($tickets, 'Tickets retrieved successfully');
    }

    // Assign a ticket to an agent
    public function assignTicketToAgent(Request $request, $ticket_id)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id', // Ensure the agent exists
        ]);

        // Check if the agent has the role of 'agent'
        $agent = User::find($request->agent_id);
        if (!$agent || $agent->role !== 'agent') {
            return $this->sendError('The specified user is not an agent.', [], 403);
        }

        $ticket = Ticket::find($ticket_id);

        if (!$ticket) {
            return $this->sendError('Ticket not found', [], 404);
        }

        // Assign the ticket to the agent
        $ticket->agent_id = $request->agent_id;
        $ticket->save();

        return $this->sendResponse($ticket, 'Ticket assigned to agent successfully');
    }

}
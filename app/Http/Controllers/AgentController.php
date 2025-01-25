<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    // Reply to a ticket
    public function replyToTicket(Request $request, $ticket_id)
    {
        $request->validate([
            'message' => 'required|string',
            'status' => 'required|in:In Progress,Resolved,Closed', // Optional status update
        ]);
        $ticket = Ticket::find($ticket_id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        // Add a reply
        $reply = Reply::create([
            'ticket_id' => $ticket->id,
            'agent_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Update ticket status
        $ticket->status = $request->status;
        $ticket->save();

        return response()->json(['message' => 'Reply added successfully', 'reply' => $reply]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\TicketRequest;

class UserController extends ResponseController
{
    // User Registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|regex:/^[\w\.-]+@[\w\.-]+\.\w+$/',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role
        ]);

        return $this->sendResponse($user->createToken('API Token')->plainTextToken, 'Registration successful');
    }

    // User Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return $this->sendResponse($user->createToken('API Token')->plainTextToken, 'Login successful');
    }

    // Create a new ticket
    public function createTicket(TicketRequest $request)
    {
        $request->validated();
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'Pending',
            'user_id' => Auth::id(),
        ]);

        return $this->sendResponse($ticket, 'Ticket created successfully');
    }

    // Get all tickets created by the authenticated user
    public function getTickets()
    {
        $tickets = Ticket::where('user_id', Auth::id())->paginate(10);

        return $this->sendResponse($tickets, 'Tickets retrieved successfully');
    }


    // Get a specific ticket details
    public function getTicket($id)
    {
        $ticket = Ticket::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found or unauthorized access'], 404);
        }

        return $this->sendResponse($ticket, 'Ticket details retrieved successfully');
    }

}
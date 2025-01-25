<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    // User Registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role
        ]);

        return response()->json([
            'message' => 'Registration successful',
            'token' => $user->createToken('API Token')->plainTextToken,
        ], 201);
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

        return response()->json([
            'message' => 'Login successful',
            'token' => $user->createToken('API Token')->plainTextToken,
        ]);
    }

    // Create a new ticket
    public function createTicket(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'Pending',
            'user_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Ticket created successfully', 'ticket' => $ticket], 201);
    }

    // Get all tickets created by the authenticated user
    public function getTickets()
    {
        $tickets = Ticket::where('user_id', Auth::id())->paginate(10);

        return response()->json($tickets);
    }


    // Get a specific ticket details
    public function getTicket($id)
    {
        $ticket = Ticket::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found or unauthorized access'], 404);
        }

        return response()->json(['ticket' => $ticket]);
    }
}

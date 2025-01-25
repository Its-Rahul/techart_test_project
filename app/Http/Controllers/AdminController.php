<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
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

        return response()->json(['message' => 'Agent created successfully', 'agent' => $agent], 201);
    }

    public function getAllTickets()
    {
        $perPage = request()->get('per_page', 10);
        $tickets = Ticket::with('user')->paginate($perPage);

        return response()->json($tickets);
    }


}

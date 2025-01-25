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

    public function getAllTickets(Request $request)
    {
        $query = Ticket::with('user');

        // Check if a priority filter is applied
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->paginate(10);
        return response()->json($tickets);
    }



}

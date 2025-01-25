<?php


use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// User routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/tickets', [UserController::class, 'createTicket']);
    Route::get('/tickets', [UserController::class, 'getTickets']);
    Route::get('/tickets/{id}', [UserController::class, 'getTicket']);
});

// Agent routes
Route::middleware(['auth:sanctum', 'role:agent'])->group(function () {
    Route::post('/tickets/{ticket_id}/reply', [AgentController::class, 'replyToTicket']);

});

// Admin routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/admin/agents', [AdminController::class, 'createAgent']);
    Route::get('/admin/tickets', [AdminController::class, 'getAllTickets']);
});

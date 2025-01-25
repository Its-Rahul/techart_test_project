# Support Ticket System API

This is a RESTful API for a Support Ticket System built using Laravel. It allows users to create support tickets, agents to reply to them, and admins to manage agents and tickets.

## Features

- **User Registration & Authentication**: Register and authenticate users with Laravel Sanctum or Passport.
- **Support Ticket Creation**: Users can create support tickets with a title, description, and status.
- **Agent Responses**: Agents can reply to tickets and update the status.
- **Role-Based Access Control**: Users, agents, and admins have specific permissions.
- **Ticket Priorities**: Assign priorities (Low, Medium, High) to tickets.
- **Pagination**: Ticket listings are paginated for efficiency.
- **API Endpoints**:
  - **POST /register**: Register a new user (returns a token).
  - **POST /login**: Login endpoint (returns a token).
  - **POST /tickets**: Create a new ticket (user only).
  - **GET /tickets**: List all tickets created by the authenticated user (user only).
  - **GET /tickets/{ticket_id}**: Get details of a specific ticket (user only).
  - **POST /tickets/{ticket_id}/reply**: Agents reply to a ticket (agent only).
  - **POST /admin/agents**: Admin creates a new agent (admin only).
  - **GET /admin/tickets**: List all tickets (admin only).

## Technologies Used

- **Laravel**: PHP framework for building the API.
- **Sanctum/Passport**: For API authentication.
- **MySQL**: For database management.
- **PHPUnit**: For unit testing.

## Installation

### Prerequisites

- PHP 8.0 or higher
- Composer
- MySQL (or any other supported database)

### Steps to Set Up

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/support-ticket-api.git
   cd support-ticket-api
2. Install dependencies:
```bash
  composer install
3. Set up the .env file:
  Copy the .env.example to .env:
  cp .env.example .env
    Configure your database and other environment variables in the .env file.
    Generate an application key:
    php artisan key:generate

4. Run migrations:
php artisan migrate
(Optional) Seed the database with dummy data:


5. php artisan db:seed
Start the development server:


6. php artisan serve

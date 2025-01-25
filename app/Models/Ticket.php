<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';
    protected $fillable = ['title', 'description', 'status', 'user_id', 'priority','agent_id'];

   protected $with = ['user', 'replies', 'agent'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
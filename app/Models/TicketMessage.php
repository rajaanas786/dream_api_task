<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    use HasFactory;

    public $table = "ticket_message";

    protected $fillable = [
        'user_id',
        'message'
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Loan extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'userid',
        'amount',
        'term',
        'status',
        'due_amount',
        'paid_amount',
    ];
}

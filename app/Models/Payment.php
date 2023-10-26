<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Payment extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'loanid',
        'adminid',
        'amount',
        'paid_amount',
        'date',
        'status',
    ];
    public $timestamps = false;
}

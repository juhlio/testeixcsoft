<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_document',
        'recipient_document',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}

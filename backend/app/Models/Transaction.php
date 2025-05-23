<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Transaction extends Model
{
    protected $fillable = ['amount', 'type', 'user_id', 'description'];

     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_PURCHASE = 'purchase';
    const TYPE_SELLER_INCOME = 'seller_income';
}
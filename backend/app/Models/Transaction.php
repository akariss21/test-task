<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'order_id',
        'seller_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array', // автоматическое преобразование JSON в массив
    ];

    /**
     * Пользователь, которому принадлежит транзакция (обычно покупатель).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Продавец, которому направлен платеж (если есть).
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Связанный заказ (если применимо).
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

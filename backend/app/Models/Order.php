<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use HasFactory;
    public $timestamps = true;
    protected $fillable = ['customer_name',
        'order_date',
        'status',
        'comment',
        'product_id',
        'quantity'
    ];
    public function products() {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
    public function getFormattedDate() {
        return $this->created_at->format('d.m.Y');
    }
    public function getStatus() {
        return $this->getAttribute('status');
    }
    public function getTotalPriceAttribute()
    {
        return $this->products->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });
    }
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}

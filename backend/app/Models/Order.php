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
    public function product() {
        return $this->belongsTo(Product::class);
    }
    public function getFormattedDate() {
        return $this->created_at->format('d.m.Y');
    }
    public function getStatus() {
        return $this->getAttribute('status');
    }
}

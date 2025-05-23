<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    use HasFactory;
    public $timestamps = true;
    protected $fillable = ['name',
        'category_id',
        'description',
        'price'
    ];
    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function getName(){
        return $this->getAttribute('name');
    }
    public function getDescription(){
        return $this->getAttribute('description');
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
    public function getSellerProfitAttribute(): float
    {
        return $this->price * 0.90; // 90% от цены
    }
}

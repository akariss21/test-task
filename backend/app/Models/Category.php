<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = ['name'];
    public function products() {
        return $this->hasMany(Product::class);
    }
    public function getName()
    {
        return $this->getAttribute('name');
    }
}

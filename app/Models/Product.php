<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'sizes', 'description', 'price', 'stock', 'category'];

    protected $casts = ['sizes' => 'array'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

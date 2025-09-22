<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
      use HasFactory;

   
     protected $fillable = [
        'vendor_id',
        'name',
        'description',
        'price',
        'stock',
        'image_url',
        'status',
    ];
   public function getImageUrlAttribute($value)
{
    if (!$value) {
        return null;
    }
    
    if (strpos($value, 'http') === 0) {
        return $value;
    }
    
    return asset('storage/' . $value);
}
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}

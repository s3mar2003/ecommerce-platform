<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CartItem;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    //  public function items()
    // {
    //     return $this->hasMany(CartItem::class);
    // }
     public function products() {
         return $this->belongsToMany(Product::class, 'cart_items')->withPivot('quantity');
     }
}

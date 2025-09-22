<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // الثوابت الخاصة بالأدوار
    const ROLE_ADMIN = 'admin';
    const ROLE_VENDOR = 'vendor';
    const ROLE_CUSTOMER = 'customer';

    // الحقول القابلة للتعيين
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // حقل الدور
        'phone',
        'address',
    ];

    // الحقول المخفية
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // الحقول مع التحويلات
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // دوال مساعدة للتحقق من الصلاحية
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isVendor()
    {
        return $this->role === self::ROLE_VENDOR;
    }

    public function isCustomer()
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    //relections
   public function products() { return $this->hasMany(Product::class, 'vendor_id'); }
    public function orders() { return $this->hasMany(Order::class, 'customer_id'); }
    public function cart() { return $this->hasOne(Cart::class, 'customer_id'); }
    public function notifications() { return $this->hasMany(Notification::class); }
    public function payments() { return $this->hasManyThrough(Payment::class, Order::class); 
    }

  public function getCartItemsCountAttribute()
{
    if (!$this->cart) {
        return 0;
    }
    
    $this->load('cart.cartItems');
    
    return $this->cart->cartItems->count();
}

public function getUnreadNotificationsCountAttribute()
{
    return $this->notifications()->where('status', 'unread')->count();
}
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['id', 'nif', 'address', 'default_payment_type', 'default_payment_ref'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }

    
    public function tshirtImages(): HasMany
    {
        return $this->hasMany(TshirtImage::class, 'customer_id');
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class OrderItem extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['order_id', 'tshirt_image_id', 'qty', 'unit_price', 'color_code', 'size', 'sub_total'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }

    public function tshirtImage(): BelongsTo
    {
        return $this->belongsTo(TshirtImage::class, 'tshirt_images_id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_code');
    }
}

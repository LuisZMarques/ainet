<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Color extends Model
{
    use HasFactory;
    protected $primaryKey = 'code';
    public $timestamps = false;
    public $incrementing= false;
    protected $keyType = 'string';
    protected $fillable = ['code', 'name'];

    public function orderItems(): BelongsToMany
    {
        return $this->BelongsToMany(OrderItem::class, 'color_code');
    }
}

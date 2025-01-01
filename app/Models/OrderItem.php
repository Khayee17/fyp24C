<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_name',
        'quantity',
        'price',
        'variant_text',
        'remark',
    ];

    public function order()
    {
        return $this->belongsTo(MyOrder::class, 'order_id', 'id');
    }
}

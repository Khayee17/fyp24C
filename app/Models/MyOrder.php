<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_count',
        'phone',
        'items',
        'subtotal',
        'sst',
        'rounding',
        'total',
        'status',
        'table_id',
    ];

    protected $casts = [
        'items' => 'array', // 自动解码JSON数据为数组
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
    
    public function userInfo()
    {
        return $this->hasOne(UserInfo::class, 'order_id', 'id');
    }
}

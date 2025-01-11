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
        'table_ids',
    ];

    protected $casts = [
        'items' => 'array', // 自动解码JSON数据为数组
        'table_ids' => 'array', // 将table_ids字段处理为数组
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
    
    // 获取与订单关联的桌位模型
    public function tables()
    {
        return Table::whereIn('id', $this->table_ids)->get();
    }

    public function userInfo()
    {
        return $this->hasOne(UserInfo::class, 'order_id', 'id');
    }
}

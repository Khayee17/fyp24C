<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone', 
        'numberOfCustomers',
        'order_id', // 确保添加 order_id 字段
    ];

    // 一对一关系：UserInfo 属于 MyOrder
    public function order()
    {
        return $this->belongsTo(MyOrder::class, 'order_id', 'id');
    }
}

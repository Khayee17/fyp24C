<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status',
        'capacity',
    ];

    // 桌位与订单的反向多对一关系
    public function orders()
    {
        return $this->hasMany(MyOrder::class, 'table_id');
    }
}

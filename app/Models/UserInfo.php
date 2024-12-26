<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone', 
        'numberOfCustomers'
    ];

    public function orders()
    {
        return $this->hasMany(MyOrder::class, 'user_info_id');
    }
}

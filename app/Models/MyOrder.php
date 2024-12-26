<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_count',
        'items',
        'subtotal',
        'sst',
        'rounding',
        'total',
    ];

    protected $casts = [
        'items' => 'array', // Automatically decode JSON data into an array
    ];
}

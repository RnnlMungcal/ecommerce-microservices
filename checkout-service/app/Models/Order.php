<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'products',
        'total',
        'status',
    ];

    protected $casts = [
        'products' => 'array', // auto-cast JSON to array
    ];
}

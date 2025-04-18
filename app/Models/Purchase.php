<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'products_id',
        'user_id',
        'member_id',
        'total_price',
        'total_payment',
        'change'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

}

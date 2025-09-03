<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'brand',
        'explain',
        'condition',
        'category',
        'item_image'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

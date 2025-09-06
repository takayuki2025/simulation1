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
        'item_image',
        'remain'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites() {
    return $this->hasMany(Good::class);
}

    public function scopeItemSearch($query, $all_item_search)
{
  if (!empty($all_item_search)) {
    $query->where('name', 'like', '%' . $all_item_search . '%');
  }
}

}

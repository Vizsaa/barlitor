<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $table = 'item';
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'title', 'description', 'cost_price', 'sell_price',
        'image_path', 'category', 'stock_quantity', 'supplier_id', 'type',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function reviews()
    {
        return $this->hasMany(ItemReview::class, 'item_id', 'item_id');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'item_id', 'item_id');
    }
}

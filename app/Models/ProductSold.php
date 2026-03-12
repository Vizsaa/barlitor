<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSold extends Model
{
    protected $table = 'products_sold';
    protected $primaryKey = 'materials_sold_id';
    public $timestamps = false;

    protected $fillable = [
        'transaction_id', 'product_id', 'quantity', 'rate_charged',
    ];

    public function order()
    {
        return $this->belongsTo(OrderInfo::class, 'transaction_id', 'orderinfo_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'product_id', 'item_id');
    }
}

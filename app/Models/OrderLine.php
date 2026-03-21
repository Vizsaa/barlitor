<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    protected $table = 'orderline';
    public $incrementing = false;
    protected $primaryKey = ['orderinfo_id', 'product_id'];
    public $timestamps = false;

    protected $fillable = [
        'orderinfo_id', 'product_id', 'quantity', 'rate',
    ];

    public function order()
    {
        return $this->belongsTo(OrderInfo::class, 'orderinfo_id', 'orderinfo_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'product_id', 'item_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $table = 'rental';
    protected $primaryKey = 'rental_id';
    public $timestamps = false;

    protected $fillable = [
        'transaction_id', 'customer_id', 'item_id',
        'start_date', 'due_date', 'rate_charged', 'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(OrderInfo::class, 'transaction_id', 'orderinfo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}

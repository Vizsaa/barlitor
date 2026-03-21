<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;

    protected $fillable = [
        'transaction_id', 'payment_type', 'amount', 'payment_date',
    ];

    public function order()
    {
        return $this->belongsTo(OrderInfo::class, 'transaction_id', 'orderinfo_id');
    }
}

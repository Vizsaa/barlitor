<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;

    protected $fillable = [
        'transaction_id', 'amount_paid', 'paid_on',
    ];

    public function order()
    {
        return $this->belongsTo(OrderInfo::class, 'transaction_id', 'orderinfo_id');
    }
}

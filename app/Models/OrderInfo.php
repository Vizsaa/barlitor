<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderInfo extends Model
{
    protected $table = 'orderinfo';
    protected $primaryKey = 'orderinfo_id';

    protected $fillable = [
        'customer_id', 'user_id', 'date_placed', 'date_shipped',
        'shipping', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function productsSold()
    {
        return $this->hasMany(ProductSold::class, 'transaction_id', 'orderinfo_id');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'transaction_id', 'orderinfo_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'transaction_id', 'orderinfo_id');
    }
}

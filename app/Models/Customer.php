<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    protected $fillable = [
        'title', 'fname', 'lname', 'addressline', 'town', 'zipcode', 'phone',
    ];

    public function orders()
    {
        return $this->hasMany(OrderInfo::class, 'customer_id', 'customer_id');
    }
}

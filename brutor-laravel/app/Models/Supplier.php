<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'name', 'contact_email', 'contact_phone', 'lead_time', 'website',
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'supplier_id', 'supplier_id');
    }
}

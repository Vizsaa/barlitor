<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'expense';
    protected $primaryKey = 'expense_id';
    public $timestamps = false;

    protected $fillable = [
        'title', 'amount', 'expense_date', 'notes',
    ];
}

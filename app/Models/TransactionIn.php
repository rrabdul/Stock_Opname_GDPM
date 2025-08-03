<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'item_name',
        'unit',
        'qty_in',
        'source',
        'user_name',
    ];
}

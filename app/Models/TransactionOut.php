<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'item_name',
        'qty_out',
        'unit',
        'destination',
        'user',
    ];
}

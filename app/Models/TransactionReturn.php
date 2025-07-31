<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionReturn extends Model
{
    use HasFactory;
    protected $fillable = [
    'item_code',
    'item_name',
    'qty_return',
    'unit',
    'source',
    'keterangan',
    'user',
];

}

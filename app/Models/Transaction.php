<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['barang_id', 'quantity', 'keterangan', 'transaction_date'];
}


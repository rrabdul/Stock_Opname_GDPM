<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBaseBarang extends Model
{
    use HasFactory;

    protected $table = 'data_base_barang'; // pastikan sesuai nama tabel di migration
    protected $fillable = [
        'item_code',
        'item_name',
        'Quantity',
        'unit',
        'Area',
        'create_date'
    ];

     public $timestamps = false;
}

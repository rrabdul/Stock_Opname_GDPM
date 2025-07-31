<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTakingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_taking_header_id',
        'item_code',
        'item_name',
        'qty_aktual',
        'user',
    ];

    // Relasi ke header
    public function header()
    {
        return $this->belongsTo(StockTakingHeader::class, 'stock_taking_header_id');
    }
}

<?php

namespace App\Exports;

use App\Models\TransactionOut;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Responsable;

class TransactionOutExport implements FromCollection, WithHeadings
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        return TransactionOut::whereBetween('created_at', [$this->from . ' 00:00:00', $this->to . ' 23:59:59'])
            ->select('item_code', 'item_name', 'qty_out', 'unit', 'user', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Item Code',
            'Item Name',
            'Qty Out',
            'Unit',
            'User',
            'Waktu Transaksi',
        ];
    }
}

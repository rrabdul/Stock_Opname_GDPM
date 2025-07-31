<?php

namespace App\Exports;

use App\Models\TransactionIn;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionInExport implements FromCollection, WithHeadings
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
        return TransactionIn::whereBetween('created_at', [$this->from . ' 00:00:00', $this->to . ' 23:59:59'])
            ->select('item_code', 'item_name', 'qty_in', 'source', 'user_name', 'created_at')
            ->get();
    }

    public function headings(): array
    {
        return ['Item Code', 'Item Name', 'Qty In', 'Source', 'User', 'Created At'];
    }
}

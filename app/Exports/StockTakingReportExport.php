<?php

namespace App\Exports;

use App\Models\StockTakingDetail;
use App\Models\DataBaseBarang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockTakingReportExport implements FromCollection, WithHeadings
{
    protected $headerId;

    public function __construct($headerId)
    {
        $this->headerId = $headerId;
    }

    public function collection()
    {
        $details = StockTakingDetail::where('stock_taking_header_id', $this->headerId)->get();

        return $details->map(function ($detail) {
            $barang = DataBaseBarang::where('item_code', $detail->item_code)->first();
            $stock_sistem = $barang?->Quantity ?? 0;

            return [
                'item_code'     => $detail->item_code,
                'item_name'     => $barang?->item_name ?? '-',
                'stock_sistem'  => $stock_sistem,
                'stock_aktual'  => $detail->qty_aktual,
                'selisih'       => $detail->qty_aktual - $stock_sistem,
            ];
        });
    }

    public function headings(): array
    {
        return ['Kode Barang', 'Nama Barang', 'Stock Sistem', 'Stock Aktual', 'Selisih'];
    }
}

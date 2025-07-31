<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\StockTakingHeader;
use App\Models\StockTakingDetail;
use App\Models\DataBaseBarang;

class StockTakingReportDetail extends Component
{
    public $header;
    public $details;

    public function mount($id)
    {
        $this->header = StockTakingHeader::findOrFail($id);

        $this->details = StockTakingDetail::where('stock_taking_header_id', $id)
            ->where('stock_taking_header_id', $id)
            ->get()
            ->map(function ($detail) {
                $item = DataBaseBarang::where('item_code', $detail->item_code)->first();
                $stock_sistem = $item?->Quantity ?? 0;

                return [
                    'item_code' => $detail->item_code,
                    'item_name' => $item?->item_name ?? '-',
                    'stock_sistem' => $stock_sistem,
                    'stock_aktual' => $detail->qty_aktual,
                    'selisih' => $detail->qty_aktual - $stock_sistem,
                ];
            });
    }

    public function barang()
    {
        return $this->belongsTo(DataBaseBarang::class, 'item_code', 'item_code');
    }


    public function render()
    {
        return view('stocktaking.reportdetail');
    }
}

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

    // Tambahan untuk sorting
    public $sortField = 'item_name';
    public $sortDirection = 'asc';

    public function mount($id)
    {
        $this->header = StockTakingHeader::findOrFail($id);
        $this->loadDetails(); // pertama kali load
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->loadDetails(); // reload dengan arah sort baru
    }

    public function loadDetails()
    {
        $rawDetails = StockTakingDetail::where('stock_taking_header_id', $this->header->id)->get();

        $mapped = $rawDetails->map(function ($detail) {
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

        $this->details = $mapped->sortBy(function ($item) {
            return $item[$this->sortField];
        }, SORT_REGULAR, $this->sortDirection === 'desc')->values();
    }

    public function render()
    {
        return view('stocktaking.reportdetail');
    }
}

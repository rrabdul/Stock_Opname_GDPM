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

    public $sortField = 'item_name';
    public $sortDirection = 'asc';

    public function mount($id)
    {
        $this->header = StockTakingHeader::findOrFail($id);
        $this->loadDetails();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->loadDetails();
    }

    public function loadDetails()
    {
        $rawDetails = StockTakingDetail::where('stock_taking_header_id', $this->header->id)->get();

        $mapped = $rawDetails->map(function ($detail) {
            $item = DataBaseBarang::where('item_code', $detail->item_code)->first();

            $stock_aktual = $detail->qty_aktual ?? 0;
            $stock_line = $detail->qty_line ?? 0;
            $unit = $item?->unit ?? '-';

            return [
                'item_code'     => $detail->item_code,
                'item_name'     => $item?->item_name ?? '-',
                'stock_aktual'  => $stock_aktual,
                'stock_line'    => $stock_line,
                'total_stock'   => $stock_aktual + $stock_line,
                'unit'          => $unit,
            ];
        });

        // Amankan agar tidak error jika sort field tidak tersedia
        $this->details = $mapped->sortBy(function ($item) {
            return $item[$this->sortField] ?? null;
        }, SORT_REGULAR, $this->sortDirection === 'desc')->values();
    }


    public function render()
    {
        return view('stocktaking.reportdetail');
    }
}

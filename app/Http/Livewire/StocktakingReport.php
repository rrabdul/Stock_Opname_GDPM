<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\StockTakingHeader;

class StockTakingReport extends Component
{
    public $reports;

    public function mount()
    {
        $this->reports = StockTakingHeader::where('status', 'Done')->latest()->get();
    }

    public function render()
    {
        return view('stocktaking.report');
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\StockTakingHeader;

class StockTakingReport extends Component
{
    public $reports;
    public $searchTerm = '';
    public $dateFrom;
    public $dateTo;

    public function mount()
    {
        $this->loadReports();
    }

    public function updatedSearchTerm()
    {
        $this->loadReports();
    }

    public function updatedDateFrom()
    {
        $this->loadReports();
    }

    public function updatedDateTo()
    {
        $this->loadReports();
    }

    public function loadReports()
    {
        $this->reports = StockTakingHeader::where('status', 'Done')
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('area', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('stocktaking.report');
    }
}

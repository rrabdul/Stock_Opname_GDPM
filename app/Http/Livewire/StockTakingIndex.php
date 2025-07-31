<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\StockTakingHeader;
use Illuminate\Support\Facades\Auth;

class StockTakingIndex extends Component
{
    public $title, $area, $showModal = false;
    public $headers;

    // Filter
    public $searchTitle = '';
    public $dateFrom;
    public $dateTo;

    public function mount()
    {
        $this->loadHeaders();
    }

    public function updated($property)
    {
        $this->loadHeaders();
    }

    public function loadHeaders()
    {
        $query = StockTakingHeader::query();

        if ($this->searchTitle) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->searchTitle . '%')
                  ->orWhere('area', 'like', '%' . $this->searchTitle . '%');
            });
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $this->headers = $query->orderBy('created_at', 'desc')->get();
    }

    public function openModal()
    {
        $this->reset(['title', 'area']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required',
            'area' => 'required',
        ]);

        StockTakingHeader::create([
            'title' =>strtoupper($this->title),
            'area' =>strtoupper($this->area),
            'status' => 'Draft',
            'created_by' => Auth::user()->name ?? 'Unknown',
        ]);

        session()->flash('message', 'Stock Taking berhasil dibuat.');
        $this->closeModal();
        $this->loadHeaders();
    }

    public function goToDetail($id)
    {
        return redirect()->route('stocktaking.detail', ['id' => $id]);
    }


    public function render()
    {
        return view('stocktaking.create');
    }

}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DataBaseBarang;
use App\Models\TransactionReturn as ReturnModel;
use Illuminate\Support\Facades\Auth;

class TransactionReturn extends Component
{
    public $barangs, $item_code, $item_name, $qty_return, $source, $keterangan, $unit;
    public $showModal = false;

    public $searchRiwayat, $dateFrom, $dateTo;
    public $history;

    public $searchQuery = '';

    public function mount()
    {
        $this->barangs = DataBaseBarang::all();
        $this->loadHistory();
    }

    public function render()
    {
        return view('transaction.return', [
            'searchResults' => $this->searchResults, // ← tetap bisa dipakai di Blade
        ]);
    }

    public function openModal()
    {
        $this->resetInput();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function resetInput()
    {
        $this->item_code = '';
        $this->item_name = '';
        $this->qty_return = '';
        $this->source = '';
        $this->keterangan = '';
        $this->unit = '';
        $this->searchQuery = '';
        $this->searchResults = [];
    }

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) >= 2) {
            $this->searchResults = DataBaseBarang::where('item_code', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('item_name', 'like', '%' . $this->searchQuery . '%')
                ->limit(10)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectItem($itemCode)
    {
        $barang = DataBaseBarang::where('item_code', $itemCode)->first();

        if ($barang) {
            $this->item_code = $barang->item_code;
            $this->item_name = $barang->item_name;
            $this->unit = $barang->unit;
            $this->searchQuery = $barang->item_code . ' - ' . $barang->item_name;
            $this->searchResults = [];
        }
    }

    public function submitReturn()
    {
        $this->validate([
            'item_code' => 'required|exists:data_base_barang,item_code',
            'qty_return' => 'required|numeric|min:1',
            'source' => 'nullable|string|max:255',
            'keterangan' => 'required|string|max:255',
        ]);

        $barang = DataBaseBarang::where('item_code', $this->item_code)->first();

        if (!$barang || $barang->Quantity < $this->qty_return) {
            $this->addError('qty_return', 'Stok tidak mencukupi untuk dikembalikan.');
            return;
        }

        ReturnModel::create([
            'item_code' => $barang->item_code,
            'item_name' => $barang->item_name,
            'qty_return' => $this->qty_return,
            'unit' => $barang->unit,
            'source' => strtoupper($this->source),
            'keterangan' => strtoupper($this->keterangan),
            'user' => strtoupper(Auth::user()->name ?? 'User'),
        ]);

        $barang->decrement('quantity', $this->qty_return);

        session()->flash('message', 'Transaksi return berhasil disimpan.');
        $this->closeModal();
        $this->loadHistory();
    }

    public function loadHistory()
    {
        $query = ReturnModel::query();

        if ($this->searchRiwayat) {
            $query->where(function ($q) {
                $q->where('item_code', 'like', '%' . $this->searchRiwayat . '%')
                    ->orWhere('item_name', 'like', '%' . $this->searchRiwayat . '%');
            });
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $this->history = $query->latest()->get();
    }

    public function updatedSearchRiwayat()
    {
        $this->loadHistory();
    }

    public function getSearchResultsProperty()
    {
        if (strlen($this->searchQuery) >= 2) {
            return DataBaseBarang::where('item_code', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('item_name', 'like', '%' . $this->searchQuery . '%')
                ->limit(10)
                ->get();
        }

        return collect();
    }

}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DataBaseBarang;
use App\Models\TransactionOut as TransactionOutModel;
use Illuminate\Support\Facades\Auth;

class TransactionOut extends Component
{
    public $barangs, $item_code, $item_name, $qty_out, $destination, $showModal = false;
    public $history;
    public $searchTerm = '';
    public $searchResults = [];
    public $searchRiwayat = '';
    public $dateFrom;
    public $dateTo;

    public function mount()
    {
        $this->barangs = DataBaseBarang::all();
        $this->loadHistory();
    }

    public function loadHistory()
    {
        $query = TransactionOutModel::query();

        if ($this->searchRiwayat) {
            $query->where(function ($q) {
                $q->where('item_code', 'like', '%' . $this->searchRiwayat . '%')
                  ->orWhere('item_name', 'like', '%' . $this->searchRiwayat . '%');
            });
        }

        if ($this->dateFrom && $this->dateTo) {
            $from = $this->dateFrom . ' 00:00:00';
            $to = $this->dateTo . ' 23:59:59';

            $query->whereBetween('created_at', [$from, $to]);
        }

        $this->history = $query->latest()->get();
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
        $this->qty_out = '';
        $this->destination = '';
        $this->searchTerm = '';
        $this->searchResults = [];
    }

    public function updatedSearchTerm()
    {
        if (strlen($this->searchTerm) > 1) {
            $this->searchResults = DataBaseBarang::where('item_code', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('item_name', 'like', '%' . $this->searchTerm . '%')
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
            $this->searchTerm = '';
            $this->searchResults = [];
        }
    }

    public function submitOut()
    {
        $this->validate([
            'item_code' => 'required',
            'qty_out' => 'required|numeric|min:1',
        ]);

        $barang = DataBaseBarang::where('item_code', $this->item_code)->first();

        if (!$barang) {
            session()->flash('message', 'Barang tidak ditemukan.');
            return;
        }

        if ($barang->Quantity < $this->qty_out) {
            session()->flash('message', 'Stok tidak cukup. Stok tersedia: ' . $barang->Quantity);
            return;
        }

        // Kurangi qty dari data barang
        $barang->Quantity -= $this->qty_out;
        $barang->save();

        // Simpan transaksi
        TransactionOutModel::create([
            'item_code' => $this->item_code,
            'item_name' => $barang->item_name,
            'qty_out' => $this->qty_out,
            'unit' => $barang->unit,
            'destination' => $this->destination,
            'user' => Auth::user()?->name ?? 'Unknown',
        ]);

        session()->flash('message', 'Stok berhasil dikurangi.');
        $this->resetInput();
        $this->loadHistory();
        $this->closeModal();
    }

    public function updatedSearchRiwayat()
    {
        $this->loadHistory();
    }

    public function updatedDateFrom()
    {
        $this->loadHistory();
    }

    public function updatedDateTo()
    {
        $this->loadHistory();
    }

    public function render()
    {
        return view('transaction.out');
    }
}

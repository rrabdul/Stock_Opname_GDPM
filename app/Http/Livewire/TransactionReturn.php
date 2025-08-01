<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DataBaseBarang;
use App\Models\TransactionReturn as ReturnModel;
use Illuminate\Support\Facades\Auth;

class TransactionReturn extends Component
{
    public $barangs, $item_code, $qty_return, $source, $keterangan, $showModal = false;
    public $searchRiwayat, $dateFrom, $dateTo;
    public $history;

    public function mount()
    {
        $this->barangs = DataBaseBarang::all();
        $this->loadHistory();
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
        $this->qty_return = '';
        $this->source = '';
    }

    public function submitReturn()
    {
                $this->validate([
            'item_code' => 'required|exists:data_base_barang,item_code',
            'qty_return' => 'required|numeric|min:1',
            'source' => 'nullable|string|max:255',
            'keterangan' => 'required|nullable|string|max:255',
        ]);

        $barang = DataBaseBarang::where('item_code', $this->item_code)->first();

        if (!$barang || $barang->Quantity < $this->qty_return) {
            $this->addError('qty_return', 'Stok tidak mencukupi untuk dikembalikan.');
            return;
        }

        // Simpan ke tabel return
        ReturnModel::create([
            'item_code' => $barang->item_code,
            'item_name' => $barang->item_name,
            'qty_return' => $this->qty_return,
            'unit' => $barang->unit,
            'source' => strtoupper ($this->source),
            'keterangan' => strtoupper ($this->keterangan),
            'user' => strtoupper (Auth::user()->name ?? 'User'),
        ]);

        // Kurangi stok
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

    public function render()
    {
        return view('transaction.return');
    }

        public function updatedSearchRiwayat()
    {
        $this->loadHistory();
    }

}

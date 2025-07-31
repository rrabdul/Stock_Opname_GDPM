<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DataBaseBarang;
use App\Models\TransactionIn as TransactionInModel;
use Illuminate\Support\Facades\Auth;

class TransactionIn extends Component
{
    public $barangs, $item_code, $item_name, $qty_in, $source, $showModal = false;
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
        $query = TransactionInModel::query();

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
        $this->qty_in = '';
        $this->source = '';
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

    public function submitIn()
    {
        $this->validate([
            'item_code' => 'required',
            'qty_in' => 'required|numeric|min:1',
        ]);

        $barang = DataBaseBarang::where('item_code', $this->item_code)->first();

        if ($barang) {
            // Tambahkan qty ke data barang
            $barang->Quantity += $this->qty_in;
            $barang->save();

            // Pastikan item_name diambil dari database
            $this->item_name = $barang->item_name;

            // Simpan transaksi
            TransactionInModel::create([
                'item_code' => $this->item_code,
                'item_name' => $this->item_name,
                'qty_in' => $this->qty_in,
                'source' => $this->source,
                'user_name' => Auth::user()?->name ?? 'Unknown',
            ]);

            session()->flash('message', 'Stok berhasil ditambahkan.');
            $this->resetInput();
            $this->loadHistory();
            $this->closeModal();
        } else {
            session()->flash('message', 'Barang tidak ditemukan.');
        }
    }


    public function render()
    {
        return view('transaction.in');
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

}

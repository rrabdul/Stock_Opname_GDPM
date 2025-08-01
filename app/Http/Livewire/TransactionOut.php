<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DataBaseBarang;
use App\Models\TransactionOut as TransactionOutModel;
use Illuminate\Support\Facades\Auth;

class TransactionOut extends Component
{
    public $barangs, $item_code, $item_name, $qty_out, $destination;
    public $showModal = false;
    public $history;
    public $searchTerm = '';
    public $searchResults = [];
    public $searchRiwayat = '';
    public $dateFrom, $dateTo;
    public $showConfirmSubmit = false;
    public $tempItems = [];

    protected $listeners = ['konfirmasiSubmit' => 'submitOut'];

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
            $query->whereBetween('created_at', [
                $this->dateFrom . ' 00:00:00',
                $this->dateTo . ' 23:59:59'
            ]);
        }

        $this->history = $query->latest()->get();
    }

    public function openModal()
    {
        $this->resetInput();
        $this->showModal = true;
        $this->showConfirmSubmit = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showConfirmSubmit = false;
    }

    public function resetInput()
    {
        $this->item_code = '';
        $this->item_name = '';
        $this->qty_out = '';
        $this->destination = ''; // reset ini hanya saat open modal
        $this->searchTerm = '';
        $this->searchResults = [];
        $this->tempItems = [];
    }

    public function updatedSearchTerm()
    {
        $term = trim($this->searchTerm);
        $this->searchResults = strlen($term) > 1
            ? DataBaseBarang::where('item_code', 'like', "%$term%")
                ->orWhere('item_name', 'like', "%$term%")
                ->limit(10)
                ->get()
            : [];
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

    public function updatedItemCode($value)
    {
        $barang = DataBaseBarang::where('item_code', $value)->first();
        $this->item_name = $barang->item_name ?? '';
    }

    public function addItemToList()
    {
        $this->validate([
            'item_code' => 'required',
            'qty_out' => 'required|numeric|min:1',
        ]);

        foreach ($this->tempItems as $item) {
            if ($item['item_code'] === $this->item_code) {
                session()->flash('message', 'Barang sudah ada di daftar.');
                return;
            }
        }

        $barang = DataBaseBarang::where('item_code', $this->item_code)->first();
        if (!$barang) {
            session()->flash('message', 'Barang tidak ditemukan.');
            return;
        }

        if ($barang->Quantity < $this->qty_out) {
            session()->flash('message', 'Stok tidak cukup. Stok tersedia: ' . $barang->Quantity);
            return;
        }

        $this->tempItems[] = [
            'item_code' => $barang->item_code,
            'item_name' => $barang->item_name,
            'qty_out' => $this->qty_out,
            'unit' => $barang->unit,
        ];

        $this->reset(['item_code', 'item_name', 'qty_out']);
    }

    public function removeItem($index)
    {
        unset($this->tempItems[$index]);
        $this->tempItems = array_values($this->tempItems);
    }

    public function submitOut()
    {
        // Validasi tujuan hanya sekali di awal
        $this->validate([
            'destination' => 'required|string|max:255',
        ]);

        foreach ($this->tempItems as $item) {
            $barang = DataBaseBarang::where('item_code', $item['item_code'])->first();

            if (!$barang) {
                session()->flash('message', 'Barang tidak ditemukan: ' . $item['item_code']);
                continue;
            }

            if ($barang->Quantity < $item['qty_out']) {
                session()->flash('message', 'Stok tidak cukup untuk ' . $item['item_code'] . '. Tersedia: ' . $barang->Quantity);
                continue;
            }

            $barang->Quantity -= $item['qty_out'];
            $barang->save();

            TransactionOutModel::create([
                'item_code' => $item['item_code'],
                'item_name' => $item['item_name'],
                'qty_out' => $item['qty_out'],
                'unit' => $item['unit'],
                'destination' => strtoupper ($this->destination),
                'user' => strtoupper(Auth::user()?->name ?? 'Unknown'),
            ]);
        }

        session()->flash('message', 'Transaksi berhasil disimpan.');
        $this->resetInput();
        $this->loadHistory();
        $this->closeModal();
    }

    public function updatedSearchRiwayat() { $this->loadHistory(); }
    public function updatedDateFrom() { $this->loadHistory(); }
    public function updatedDateTo() { $this->loadHistory(); }

    public function render()
    {
        return view('transaction.out');
    }
}

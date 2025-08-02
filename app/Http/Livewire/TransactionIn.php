<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DataBaseBarang;
use App\Models\TransactionIn as TransactionInModel;
use Illuminate\Support\Facades\Auth;

class TransactionIn extends Component
{
    public $barangs, $item_code, $item_name, $unit, $qty_in, $source;
    public $showModal = false;
    public $showConfirmSubmit = false;

    public $history;
    public $searchQuery = ''; // untuk pencarian barang saat input
    public $searchResults = [];

    public $searchRiwayat = '';
    public $dateFrom;
    public $dateTo;

    public $tempItems = []; // List barang sementara sebelum submit

    public function mount()
    {
        $this->barangs = DataBaseBarang::all();
        $this->loadHistory();
    }

    public function render()
    {
        return view('transaction.in');
    }

    public function openModal()
    {
        $this->resetInput();
        $this->tempItems = [];
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
        $this->unit = '';
        $this->qty_in = '';
        $this->source = '';
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

    public function updatedItemCode($value)
    {
        $barang = DataBaseBarang::where('item_code', $value)->first();
        if ($barang) {
            $this->item_name = $barang->item_name;
            $this->unit = $barang->unit;
        } else {
            $this->item_name = '';
            $this->unit = '';
        }
    }

    public function addItemToList()
    {
        $this->validate([
            'item_code' => 'required',
            'qty_in' => 'required|numeric|min:1',
        ]);

        // Cek duplikat
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

        $this->tempItems[] = [
            'item_code' => $barang->item_code,
            'item_name' => $barang->item_name,
            'unit' => $barang->unit,
            'qty_in' => $this->qty_in,
        ];

        $this->resetInput();
    }

    public function removeItem($index)
    {
        unset($this->tempItems[$index]);
        $this->tempItems = array_values($this->tempItems); // re-index array
    }

    public function submitTransactionIn()
    {
        if (count($this->tempItems) === 0) {
            session()->flash('message', 'Tidak ada barang yang ditambahkan.');
            return;
        }

        foreach ($this->tempItems as $item) {
            $barang = DataBaseBarang::where('item_code', $item['item_code'])->first();

            if ($barang) {
                $barang->Quantity += $item['qty_in'];
                $barang->save();

                TransactionInModel::create([
                    'item_code' => $item['item_code'],
                    'item_name' => $item['item_name'],
                    'unit' => strtoupper($item['unit']),
                    'qty_in' => $item['qty_in'],
                    'source' => strtoupper($this->source),
                    'user_name' => strtoupper(Auth::user()?->name ?? 'Unknown'),
                ]);
            }
        }

        session()->flash('message', 'Transaksi IN berhasil disimpan.');
        $this->resetInput();
        $this->tempItems = [];
        $this->showConfirmSubmit = false;
        $this->loadHistory();
        $this->closeModal();
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

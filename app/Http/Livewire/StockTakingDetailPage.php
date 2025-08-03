<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\StockTakingDetail;
use App\Models\StockTakingHeader;
use App\Models\DataBaseBarang;
use Illuminate\Support\Facades\Auth;

class StockTakingDetailPage extends Component
{
    public $header;
    public $details = [];
    public $barangs = [];

    public $item_code, $item_name, $qty_aktual, $qty_line;
    public $editingId = null;
    public $showModal = false;

    public $searchTitle = '';
    public $dateFrom = null;
    public $dateTo = null;

    public $searchItem = '';
    public $showConfirmSubmit = false;

    public $searchQuery = '';
    public $searchResults = [];
    public $unit;

    public function mount($id)
    {
        $this->header = StockTakingHeader::findOrFail($id);
        $this->barangs = DataBaseBarang::all();
        $this->loadDetails();
    }

    public function render()
    {
        return view('stocktaking.detail', [
            'details' => $this->details,
            'barangs' => $this->barangs,
            'header' => $this->header,
        ]);
    }

    public function updatedSearchItem()
    {
        $this->loadDetails();
    }

    public function loadDetails()
    {
        $this->details = StockTakingDetail::where('stock_taking_header_id', $this->header->id)
            ->when($this->searchItem, function ($query) {
                $query->where(function ($q) {
                    $q->where('item_name', 'like', '%' . $this->searchItem . '%')
                      ->orWhere('item_code', 'like', '%' . $this->searchItem . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function openModal()
    {
        $this->resetInput();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->item_code = '';
        $this->item_name = '';
        $this->qty_aktual = '';
        $this->qty_line = '';
        $this->editingId = null;
        $this->searchQuery = '';
        $this->searchResults = [];
    }

    public function updatedItemCode($value)
    {
        $barang = DataBaseBarang::where('item_code', $value)->first();
        $this->item_name = $barang ? $barang->item_name : '';
    }

    public function edit($id)
    {
        $detail = StockTakingDetail::findOrFail($id);
        $this->editingId = $id;
        $this->item_code = $detail->item_code;
        $this->item_name = $detail->item_name;
        $this->qty_aktual = $detail->qty_aktual;
        $this->qty_line = $detail->qty_line;
        $this->unit = DataBaseBarang::where('item_code', $detail->item_code)->value('unit');
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'item_code' => 'required',
            'item_name' => 'required',
            'qty_aktual' => 'nullable|numeric|min:0',
            'qty_line' => 'nullable|numeric|min:0',
        ]);

        // Ubah nilai kosong jadi 0
        $qtyAktual = $this->qty_aktual !== null && $this->qty_aktual !== '' ? $this->qty_aktual : 0;
        $qtyLine   = $this->qty_line !== null && $this->qty_line !== '' ? $this->qty_line : 0;

        if (!$this->editingId) {
            $exists = StockTakingDetail::where('stock_taking_header_id', $this->header->id)
                ->where('item_code', $this->item_code)
                ->exists();

            if ($exists) {
                $this->closeModal();
                session()->flash('error', 'Barang ini sudah ada. Tidak boleh input ganda.');
                return;
            }
        }

        if ($this->editingId) {
            $detail = StockTakingDetail::findOrFail($this->editingId);
            $detail->update([
                'item_code' => $this->item_code,
                'item_name' => $this->item_name,
                'qty_aktual' => $qtyAktual,
                'qty_line' => $qtyLine,
                'unit' => $this->unit,
                'user' => Auth::user()->name ?? 'Guest',
            ]);
        } else {
            StockTakingDetail::create([
                'stock_taking_header_id' => $this->header->id,
                'item_code' => $this->item_code,
                'item_name' => $this->item_name,
                'qty_aktual' => $qtyAktual,
                'qty_line' => $qtyLine,
                'unit' => $this->unit,
                'user' => Auth::user()->name ?? 'Guest',
            ]);
        }

        session()->flash('message', 'Barang berhasil disimpan.');
        $this->resetInput();
        $this->closeModal();
        $this->loadDetails();
    }


    public function confirmSubmit()
    {
        $this->showConfirmSubmit = true;
    }

    public function submitStockTaking()
    {
        $this->header->status = 'Done';
        $this->header->submitted_by = Auth::user()?->name ?? 'Unknown';
        $this->header->save();

        session()->flash('message', 'Stock Opname berhasil disubmit.');
        return redirect()->route('stocktaking.detail', ['id' => $this->header->id]);
    }

    public function refreshPage()
    {
        $this->loadDetails();
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
            $this->unit = $barang->unit; // ambil unit di sini
            $this->searchQuery = $barang->item_code . ' - ' . $barang->item_name;
            $this->searchResults = [];
        }
    }


}


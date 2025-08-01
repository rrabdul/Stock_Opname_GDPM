<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\StockTakingDetail;
use App\Models\StockTakingHeader;
use App\Models\DataBaseBarang;
use Illuminate\Support\Facades\Auth;

class StockTakingDetailPage extends Component
{
    // Data utama
    public $header;
    public $details = [];
    public $barangs = [];

    // Form input
    public $item_code, $item_name, $qty_aktual;
    public $editingId = null;
    public $showModal = false;

    public $searchTitle = '';
    public $dateFrom = null;
    public $dateTo = null;

    // Pencarian daftar barang di tabel
    public $searchItem = '';

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
        $this->editingId = null;
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
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'item_code' => 'required',
            'item_name' => 'required',
            'qty_aktual' => 'required|integer|min:0',
        ]);

        if ($this->editingId) {
            $detail = StockTakingDetail::findOrFail($this->editingId);
            $detail->update([
                'item_code' => $this->item_code,
                'item_name' => $this->item_name,
                'qty_aktual' => $this->qty_aktual,
                'user' => Auth::user()->name ?? 'Guest',
            ]);
        } else {
            StockTakingDetail::create([
                'stock_taking_header_id' => $this->header->id,
                'item_code' => $this->item_code,
                'item_name' => $this->item_name,
                'qty_aktual' => $this->qty_aktual,
                'user' => Auth::user()->name ?? 'Guest',
            ]);
        }

        session()->flash('message', 'Barang berhasil disimpan.');
        $this->closeModal();
        $this->loadDetails();
    }

    public $showConfirmSubmit = false;

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



}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DataBaseBarang;

class Stock extends Component
{
    public $item_code, $item_name, $unit, $Area, $create_date;
    public $barangId; // untuk keperluan edit
    public $barangs;
    public $showModal = false;
    public $isEditMode = false;
    public $searchTerm = ''; // pencarian

    public function mount()
    {
        $this->loadBarangs();
    }

    public function updatedSearchTerm()
    {
        $this->loadBarangs();
    }

    public function loadBarangs()
    {
        $this->barangs = DataBaseBarang::when($this->searchTerm, function ($query) {
            $query->where('item_code', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('item_name', 'like', '%' . $this->searchTerm . '%');
        })->get();
    }

    public function openModal()
    {
        $this->resetInput();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function resetInput()
    {
        $this->barangId = null;
        $this->item_code = '';
        $this->item_name = '';
        $this->Quantity = '';
        $this->unit = '';
        $this->Area = '';
        $this->create_date = '';
    }

    public function edit($id)
    {
        $barang = DataBaseBarang::findOrFail($id);
        $this->barangId = $id;
        $this->item_code = $barang->item_code;
        $this->item_name = $barang->item_name;
        $this->Quantity = $barang->Quantity;
        $this->unit = $barang->unit;
        $this->Area = $barang->Area;
        $this->create_date = $barang->create_date;
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'item_code' => 'required|digits:10|unique:data_base_barang,item_code,' . $this->barangId,
            'item_name' => 'required',
            'Quantity' => 'numeric',
            'create_date' => 'required|date',
        ]);

        $this->item_name = strtoupper($this->item_name);

        DataBaseBarang::updateOrCreate(
            ['id' => $this->barangId],
            [
                'item_code' => $this->item_code,
                'item_name' => $this->item_name,
                'Quantity' => $this->Quantity,
                'unit' => $this->unit,
                'Area' => $this->Area,
                'create_date' => $this->create_date,
            ]
        );

        session()->flash('message', $this->isEditMode ? 'Data berhasil diupdate.' : 'Data berhasil disimpan.');
        $this->closeModal();
        $this->loadBarangs();
    }

    public function delete()
    {
        if ($this->barangId) {
            DataBaseBarang::find($this->barangId)->delete();
            session()->flash('message', 'Data berhasil dihapus.');
            $this->closeModal();
            $this->loadBarangs();
        }
    }

    public function render()
    {
        return view('barang.stock');
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DataBaseBarang;

class BarangCreate extends Component
{
    public $item_code, $item_name, $unit, $Area, $create_date;

    public function save()
    {
        $this->validate([
            'item_code' => 'required|unique:data_base_barang',
            'item_name' => 'required',
            'unit' => 'nullable',
            'Area' => 'nullable',
            'create_date' => 'required|date',
        ]);

        DataBaseBarang::create([
            'item_code' => $this->item_code,
            'item_name' => $this->item_name,
            'unit' => $this->unit,
            'Area' => $this->Area,
            'create_date' => $this->create_date,
        ]);

        session()->flash('message', 'Barang berhasil ditambahkan.');
        return redirect()->route('stock.index'); // pastikan route ini ADA
    }

    public function render()
    {
        return view('livewire.barang-create');
    }
}

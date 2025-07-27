<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DataBaseBarang;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionCreate extends Component
{
    public $barang_id;
    public $quantity;
    public $transaction_date;
    public $keterangan;

    public function mount()
    {
        $this->transaction_date = Carbon::now()->toDateString();
    }

    public function save()
    {
        $this->validate([
            'barang_id' => 'required|exists:data_base_barang,id',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
        ]);

        // Simpan transaksi
        Transaction::create([
            'barang_id' => $this->barang_id,
            'quantity' => $this->quantity,
            'keterangan' => $this->keterangan,
            'transaction_date' => $this->transaction_date,
        ]);

        // Update stok
        $barang = DataBaseBarang::find($this->barang_id);
        $barang->quantity += $this->quantity;
        $barang->save();

        session()->flash('success', 'Transaksi berhasil disimpan & stok diperbarui.');

        // Reset form
        $this->reset(['barang_id', 'quantity', 'keterangan']);
    }

    public function render()
    {
        $barangs = DataBaseBarang::all(); // Ambil data barang
        return view('livewire.transaction-create', compact('barangs'));
    }


}

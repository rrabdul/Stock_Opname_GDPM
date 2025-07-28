<div>
    <h2 class="h4 mb-4">Tambah Barang</h2>

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label>Item Code</label>
            <input type="text" class="form-control" wire:model="item_code">
            @error('item_code') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3">
            <label>Item Name</label>
            <input type="text" class="form-control" wire:model="item_name">
            @error('item_name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3">
            <label>Unit</label>
            <input type="text" class="form-control" wire:model="unit">
        </div>
        <div class="mb-3">
            <label>Area</label>
            <input type="text" class="form-control" wire:model="Area">
        </div>
        <div class="mb-3">
            <label>Create Date</label>
            <input type="date" class="form-control" wire:model="create_date">
            @error('create_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('stock.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<div>
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label for="barang_id" class="form-label">Pilih Barang</label>
            <select wire:model="barang_id" class="form-control" id="barang_id">
                <option value="">-- Pilih --</option>
                @foreach($barangs as $barang)
                    <option value="{{ $barang->id }}">{{ $barang->item_code }} - {{ $barang->item_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Jumlah Barang Masuk</label>
            <input type="number" class="form-control" wire:model="quantity">
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Transaksi</label>
            <input type="date" class="form-control" wire:model="transaction_date">
        </div>

        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <input type="text" class="form-control" wire:model="keterangan">
        </div>

        <button type="submit" class="btn btn-success">Simpan Transaksi</button>

        @if (session()->has('success'))
            <div class="alert alert-success mt-2">
                {{ session('success') }}
            </div>
        @endif
    </form>
</div>

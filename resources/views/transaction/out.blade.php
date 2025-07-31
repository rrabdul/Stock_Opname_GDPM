<div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-2 mb-md-0">
            <h2 class="h4">Transaksi Keluar</h2>
            <div class="row mb-1">
                <div class="col-md-4">
                    {{-- Kosong (bisa untuk pencarian lanjutan kalau perlu) --}}
                </div>
            </div>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ url('/export-transaction-out') }}?from={{ $dateFrom ?? '' }}&to={{ $dateTo ?? '' }}" class="btn btn-outline-success me-2">
                <i class="fas fa-file-excel me-1"></i> Export Transaksi OUT
            </a>
            <button wire:click="openModal" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Transaksi
            </button>
        </div>
    </div>

    <div class="row mb-2 align-items-end">
        <div class="col-md-2">
            <label class="form-label small">Cari Barang</label>
            <input type="text" class="form-control form-control-sm py-1" placeholder="Kode atau Nama Barang..." wire:model.debounce.500ms="searchRiwayat">
        </div>
        <div class="col-md-2">
            <label class="form-label small">Dari Tanggal</label>
            <input type="date" class="form-control form-control-sm py-1" wire:model="dateFrom">
        </div>
        <div class="col-md-2">
            <label class="form-label small">Sampai Tanggal</label>
            <input type="date" class="form-control form-control-sm py-1" wire:model="dateTo">
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mt-2">
            {{ session('message') }}
        </div>
    @endif

    {{-- Modal Tambah Transaksi --}}
@if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="submitOut">
                    <div class="modal-header" style="background-color: #1f2937;">
                        <h5 class="modal-title text-white">Transaksi Keluar</h5>
                        <button type="button" class="btn-close" wire:click="closeModal" style="filter: invert(1);"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Kode Barang</label>
                            <select wire:model="item_code" class="form-select">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->item_code }}">{{ $barang->item_code }} - {{ $barang->item_name }}</option>
                                @endforeach
                            </select>
                            @error('item_code') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label>Jumlah Keluar</label>
                            <input type="number" class="form-control" wire:model="qty_out">
                            @error('qty_out') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label>Tujuan / Keterangan</label>
                            <input type="text" class="form-control" wire:model="destination">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button> {{-- ganti dari btn-danger ke btn-success --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

    {{-- Tabel Riwayat Transaksi --}}
    <div class="card card-body border-0 shadow table-wrapper table-responsive mt-3">
        <h5>Riwayat Transaksi Keluar</h5>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Qty Out</th>
                    <th>Unit</th>
                    <th>User</th>
                    <th>Waktu Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($history as $trx)
                    <tr>
                        <td>{{ $trx->item_code }}</td>
                        <td>{{ $trx->item_name }}</td>
                        <td>{{ $trx->qty_out }}</td>
                        <td>{{ $trx->unit }}</td>
                        <td>{{ $trx->user }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">Belum ada transaksi keluar</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

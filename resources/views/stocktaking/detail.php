<div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-2 mb-md-0">
            <h2 class="h4">Stock Taking: {{ $header->title }}</h2>
            <p class="mb-1"><strong>Area:</strong> {{ $header->area }}</p>
            <p><strong>Status:</strong> <span class="badge bg-info">{{ $header->status }}</span></p>
        </div>
        <div>
            @if ($header->status !== 'Done')
                <button wire:click="openModal" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-1"></i> Tambah Barang
                </button>
                <button wire:click="submitStockTaking" class="btn btn-success">
                    <i class="fas fa-check me-1"></i> Submit Stock Taking
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mt-2">
            {{ session('message') }}
        </div>
    @endif

    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5); z-index:1050;" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <form wire:submit.prevent="save">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                @if ($editingId)
                                    Edit Barang
                                @else
                                    Tambah Barang
                                @endif
                            </h5>
                            <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                        </div>

                        <div class="modal-body">
                            {{-- Kode Barang --}}
                            <div class="mb-3">
                                <label for="item_code">Kode Barang</label>
                                <select wire:model.defer="item_code" id="item_code" class="form-select">
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach ($barangs as $barang)
                                        <option value="{{ $barang->item_code }}">
                                            {{ $barang->item_code }} - {{ $barang->item_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_code') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            {{-- Nama Barang --}}
                            <div class="mb-3">
                                <label for="item_name">Nama Barang</label>
                                <input type="text" id="item_name" class="form-control" wire:model="item_name" readonly>
                            </div>

                            {{-- Qty Aktual --}}
                            <div class="mb-3">
                                <label for="qty_aktual">Qty Aktual</label>
                                <input type="number" id="qty_aktual" class="form-control" wire:model.defer="qty_aktual">
                                @error('qty_aktual') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Tabel Detail Barang --}}
    <div class="card card-body border-0 shadow table-wrapper table-responsive mt-3">
        <h5>Daftar Barang</h5>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Qty Aktual</th>
                    <th>User</th>
                    <th>Update</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($details as $detail)
                    <tr>
                        <td>{{ $detail->item_code }}</td>
                        <td>{{ $detail->item_name }}</td>
                        <td>{{ $detail->qty_aktual }}</td>
                        <td>{{ $detail->user }}</td>
                        <td>{{ \Carbon\Carbon::parse($detail->updated_at)->format('d M Y H:i') }}</td>
                        <td>
                            @if ($header->status !== 'Done')
                                <button wire:click="edit({{ $detail->id }})" class="btn btn-sm btn-outline-primary">Edit</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">Belum ada data barang</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

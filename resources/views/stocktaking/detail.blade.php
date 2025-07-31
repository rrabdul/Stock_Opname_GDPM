<div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <div class="d-block mb-2 mb-md-0">
                <h2 class="h4">{{ $header->title }}</h2>
                <table>
                    <tr>
                        <td class="pe-2"><strong>AREA</strong></td>
                        <td class="pe-2">:</td>
                        <td>{{ $header->area }}</td>
                    </tr>
                    <tr>
                        <td class="pe-2"><strong>Status</strong></td>
                        <td class="pe-2">:</td>
                        <td>
                            <span class="badge {{ $header->status === 'Done' ? 'bg-success' : 'bg-info' }}">
                                {{ $header->status }}
                            </span>
                        </td>
                        </tr>
                </table>
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

<div class="row mb-3 align-items-end">
    <div class="col-md-3">
        <label class="form-label small">Cari Judul</label>
        <input type="text" class="form-control form-control-sm py-1" placeholder="Judul atau Area..." wire:model.debounce.500ms="searchTitle">
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

    {{-- Modal Tambah/Edit --}}
    {{-- Modal Tambah/Edit --}}
<div class="modal fade @if($showModal) show d-block @endif"
     style="background-color: rgba(0,0,0,0.5); z-index:1050; @if(!$showModal) display: none; @endif">
    <div class="modal-dialog">
        <div class="modal-content">
            <form wire:submit.prevent="save">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">{{ $editingId ? 'Edit Barang' : 'Tambah Barang' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kode Barang</label>
                        <select wire:model="item_code" class="form-select">
                            <option value="">-- Pilih Barang --</option>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->item_code }}">
                                    {{ $barang->item_code }} - {{ $barang->item_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('item_code') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" wire:model="item_name" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Qty Aktual</label>
                        <input type="number" class="form-control" wire:model.defer="qty_aktual">
                        @error('qty_aktual') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="closeModal" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


    {{-- Tabel --}}
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
                    <tr>
                        <td colspan="6">Belum ada data barang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

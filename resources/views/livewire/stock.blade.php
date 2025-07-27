<div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <h2 class="h4">Data of all items</h2>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ url('/export-barang') }}" class="btn btn-outline-success me-2">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
            <button class="btn btn-primary" wire:click="openModal">
                <i class="fas fa-plus me-1"></i> Tambah Barang
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mt-2">
            {{ session('message') }}
        </div>
    @endif

    <div class="card card-body border-0 shadow table-wrapper table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Area</th>
                    <th>Create Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangs as $barang)
                    <tr>
                        <td>{{ $barang->item_code }}</td>
                        <td>{{ $barang->item_name }}</td>
                        <td>{{ $barang->Quantity }}</td>
                        <td>{{ $barang->unit }}</td>
                        <td>{{ $barang->Area }}</td>
                                                <td>{{ \Carbon\Carbon::parse($barang->create_date)->format('d M Y') }}</td>
                        <td>
                            <button wire:click="edit({{ $barang->id }})" class="btn btn-sm btn-warning">Edit</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form wire:submit.prevent="save">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-box-open me-2"></i>
                                {{ $isEditMode ? 'Edit Data Barang' : 'Tambah Data Barang' }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Item Code</label>
                                <input type="text" class="form-control" wire:model="item_code">
                                @error('item_code') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label>Item Name</label>
                                <input type="text" class="form-control" wire:model="item_name">
                                @error('item_name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label>Quantity</label>
                                <input type="text" class="form-control" wire:model="Quantity">
                                @error('Quantity') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label>Unit</label>
                                <select class="form-select" wire:model="unit">
                                    <option value="">-- Pilih Unit --</option>
                                    <option value="PCS">PCS</option>
                                    <option value="KG">KG</option>
                                    <option value="LBR">LBR</option>
                                    <option value="ROLL">ROLL</option>
                                    <option value="SET">SET</option>
                                    <option value="BAG">BAG</option>
                                    <option value="CAR">CAR</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Area</label>
                                <select class="form-select" wire:model="Area">
                                    <option value="">-- Pilih Area --</option>
                                    <option value="GDPM">GDPM</option>
                                    <option value="GDFG">GDFG</option>
                                    <option value="GDRM">GDRM</option>
                                    <option value="GDSP">GDSP</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Create Date</label>
                                <input type="date" class="form-control" wire:model="create_date">
                                @error('create_date') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            @if($isEditMode)
                                <button type="button" wire:click="delete" class="btn btn-danger me-auto">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </button>
                            @endif
                            <button type="button" wire:click="closeModal" class="btn btn-secondary">Batal</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<div>
    {{-- Header dan Tombol --}}
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-2 mb-md-0">
            <h2 class="h4">Transaksi Masuk</h2>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ url('/export-transaction-in?from=' . $dateFrom . '&to=' . $dateTo) }}" class="btn btn-outline-success me-2">
                <i class="fas fa-file-excel me-1"></i> Export Transaksi IN
            </a>
            <button wire:click="openModal" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Transaksi
            </button>
        </div>
    </div>

    {{-- Filter --}}
    <div class="row mb-2 align-items-end">
        <div class="col-md-2">
            <input type="text" class="form-control form-control-sm py-1" placeholder="Search for Items..." wire:model.debounce.500ms="searchRiwayat">
        </div>
        <div class="col-md-2">
            <input type="date" class="form-control form-control-sm py-1" wire:model="dateFrom">
        </div>
        <div class="col-md-2">
            <input type="date" class="form-control form-control-sm py-1" wire:model="dateTo">
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="alert alert-success mt-2">
            {{ session('message') }}
        </div>
    @endif

    {{-- Modal Tambah Transaksi --}}
    @if ($showModal)
        <div class="modal-backdrop fade show" style="z-index:1040;"></div>
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5); z-index:1055;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form wire:submit.prevent="submitTransactionIn">
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title">Transaksi Masuk</h5>
                            <button type="button" class="btn-close" wire:click="closeModal" style="filter: invert(1);"></button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            {{-- Input --}}
<div class="mb-3 position-relative" wire:click.away="$set('searchResults', [])">
    <label>Kode / Nama Barang</label>
    <input type="text" class="form-control" wire:model.debounce.300ms="searchQuery" placeholder="Ketik kode/nama barang...">

    @if (!empty($searchResults))
        <ul class="list-group mt-1 position-absolute w-100" style="max-height: 200px; overflow-y: auto; z-index: 2000;">
            @foreach ($searchResults as $result)
                <li class="list-group-item list-group-item-action"
                    wire:click="selectItem('{{ $result->item_code }}')">
                    {{ $result->item_code }} - {{ $result->item_name }}
                </li>
            @endforeach
        </ul>
    @endif

    @error('item_code') <small class="text-danger">{{ $message }}</small> @enderror
</div>


                            <div class="mb-3">
                                <label>Jumlah Masuk</label>
                                <input type="number" class="form-control" wire:model="qty_in">
                                @error('qty_in') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label>Sumber Barang</label>
                                <input type="text" class="form-control" wire:model="source">
                                @error('source') <small class="text-danger text-sm">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <button type="button" wire:click="addItemToList" class="btn btn-secondary w-100">
                                    <i class="fas fa-plus-circle me-1"></i> Tambah ke Daftar
                                </button>
                                @if (session()->has('message'))
                                    <div class="mt-2 text-danger small">{{ session('message') }}</div>
                                @endif
                            </div>

                            {{-- Tabel Temp --}}
                            @if(count($tempItems) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Kode</th>
                                                <th>Nama</th>
                                                <th>Qty</th>
                                                <th>Unit</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tempItems as $index => $item)
                                                <tr>
                                                    <td>{{ $item['item_code'] }}</td>
                                                    <td>{{ $item['item_name'] }}</td>
                                                    <td>{{ $item['qty_in'] }}</td>
                                                    <td>{{ $item['unit'] }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-danger" wire:click="removeItem({{ $index }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click="closeModal" class="btn btn-secondary">Batal</button>
                            <button type="button"
                                class="btn btn-success"
                                @if(count($tempItems) == 0) disabled @endif
                                wire:click="$set('showConfirmSubmit', true)">
                                <i class="fas fa-save me-1"></i> Submit Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Riwayat Transaksi --}}
    <div class="card card-body border-0 shadow table-wrapper table-responsive mt-3">
        <h5>Riwayat Transaksi Masuk</h5>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Qty In</th>
                    <th>Unit</th>
                    <th>Source</th>
                    <th>User</th>
                    <th>Transaction Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($history as $trx)
                    <tr>
                        <td>{{ $trx->item_code }}</td>
                        <td>{{ $trx->item_name }}</td>
                        <td>{{ $trx->qty_in }}</td>
                        <td>{{ $trx->unit }}</td>
                        <td>{{ $trx->source }}</td>
                        <td>{{ $trx->user_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">Belum ada transaksi masuk</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Konfirmasi --}}
    @if ($showConfirmSubmit)
        <div class="modal-backdrop fade show" style="z-index:1040;"></div>
        <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5); z-index:1060;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">Konfirmasi Submit</h5>
                        <button type="button" class="btn-close" wire:click="$set('showConfirmSubmit', false)" style="filter: invert(1);"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah kamu yakin ingin <strong>submit</strong> transaksi ini? Setelah disubmit, data akan disimpan dan stok akan bertambah.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('showConfirmSubmit', false)">Batal</button>
                        <button class="btn btn-success" wire:click="submitTransactionIn">Ya, Submit</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

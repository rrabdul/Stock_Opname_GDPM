<div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-2 mb-md-0">
            <h2 class="h4">Create Stock Opname</h2>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button wire:click="openModal" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Create Stock Opname
            </button>
        </div>
    </div>

    {{-- Filter --}}
    <div class="row mb-3 align-items-end">
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm py-1" placeholder="Search Title or Area" wire:model.debounce.500ms="searchTitle">
        </div>
        <div class="col-md-2">
            <input type="date" class="form-control form-control-sm py-1" wire:model="dateFrom">
        </div>
        <div class="col-md-2">
            <input type="date" class="form-control form-control-sm py-1" wire:model="dateTo">
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mt-2">
            {{ session('message') }}
        </div>
    @endif

    {{-- Modal --}}
    @if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Create Stock Taking</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Stock Taking Title</label>
                            <input type="text" class="form-control" wire:model="title" required style="text-transform: uppercase;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Area</label>
                            <input type="text" class="form-control" wire:model="area" required style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Tabel Stock Taking --}}
    <div class="card card-body border-0 shadow table-wrapper table-responsive mt-3">
        <h5>Daftar Stock Opname</h5>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Stock Opname Name</th>
                    <th>Area</th>
                    <th>Status</th>
                    <th>User</th>
                    <th>Create Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($headers as $header)
                    <tr wire:click="goToDetail({{ $header->id }})" style="cursor:pointer;" class="align-middle">
                        <td>{{ $header->title }}</td>
                        <td>{{ $header->area }}</td>
                        <td>
                            <span class="badge {{ $header->status === 'Done' ? 'bg-success' : 'bg-info' }}">
                                {{ $header->status }}
                            </span>
                        </td>
                        <td>{{ $header->created_by }}</td>
                        <td>{{ \Carbon\Carbon::parse($header->created_at)->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Belum ada data stock taking.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

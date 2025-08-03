<div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <h2 class="h4">Stock Opname Report</h2>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Search Title or Area..." wire:model.debounce.500ms="searchTerm">
        </div>
        <div class="col-md-2">
            <input type="date" class="form-control" wire:model="dateFrom">
        </div>
        <div class="col-md-2">
            <input type="date" class="form-control" wire:model="dateTo">
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
                    <th>Stock Opname Name</th>
                    <th>Area</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Submit By</th> {{-- Kolom tambahan --}}
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->title }}</td>
                        <td>{{ $report->area }}</td>
                        <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d M Y H:i') }}</td>
                        <td><span class="badge bg-success">{{ $report->status }}</span></td>
                        <td>{{ $report->submitted_by ?? '-' }}</td> {{-- Tampilkan submit by --}}
                        <td>
                            <a href="{{ route('stocktaking.reportdetail', $report->id) }}" class="btn btn-sm btn-primary">
                                View Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

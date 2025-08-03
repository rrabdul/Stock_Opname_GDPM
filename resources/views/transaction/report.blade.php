<div class="container py-4">
    <h4 class="mb-3">Report Transaction</h4>

    {{-- Filter tanggal --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <label>From Date</label>
            <input type="date" class="form-control" wire:model="dateFrom">
        </div>
        <div class="col-md-3">
            <label>To Date</label>
            <input type="date" class="form-control" wire:model="dateTo">
        </div>
    </div>

    {{-- Transaction In --}}
    <div class="card mb-4">
        <div class="card-header bg-success text-white">Transaction In</div>
        <div class="card-body p-0">
            <table class="table table-striped m-0">
                <thead>
                    <tr>
                        <th>Item Code</th>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Sumber</th>
                        <th>User</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inTransactions as $trx)
                        <tr>
                            <td>{{ $trx->item_code }}</td>
                            <td>{{ $trx->item_name }}</td>
                            <td>{{ $trx->qty }}</td>
                            <td>{{ $trx->source }}</td>
                            <td>{{ $trx->user }}</td>
                            <td>{{ $trx->created_at }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Transaction Out --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Transaction Out</div>
        <div class="card-body p-0">
            <table class="table table-striped m-0">
                <thead>
                    <tr>
                        <th>Item Code</th>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Tujuan</th>
                        <th>User</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($outTransactions as $trx)
                        <tr>
                            <td>{{ $trx->item_code }}</td>
                            <td>{{ $trx->item_name }}</td>
                            <td>{{ $trx->qty }}</td>
                            <td>{{ $trx->destination }}</td>
                            <td>{{ $trx->user }}</td>
                            <td>{{ $trx->created_at }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Transaction Return --}}
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">Transaction Return</div>
        <div class="card-body p-0">
            <table class="table table-striped m-0">
                <thead>
                    <tr>
                        <th>Item Code</th>
                        <th>Name</th>
                        <th>Qty Return</th>
                        <th>Sumber</th>
                        <th>User</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returnTransactions as $trx)
                        <tr>
                            <td>{{ $trx->item_code }}</td>
                            <td>{{ $trx->item_name }}</td>
                            <td>{{ $trx->qty }}</td>
                            <td>{{ $trx->source }}</td>
                            <td>{{ $trx->user }}</td>
                            <td>{{ $trx->created_at }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

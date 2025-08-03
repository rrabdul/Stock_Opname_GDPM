<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-2 mb-md-0">
        <h2 class="h4">REPORT DETAIL {{ $header->title }}</h2>
        <table>
            <tr>
                <td class="pe-2"><strong>AREA</strong></td>
                <td class="pe-2">:</td>
                <td>{{ $header->area }}</td>
            </tr>
            <tr>
                <td class="pe-2"><strong>Date</strong></td>
                <td class="pe-2">:</td>
                <td>{{ \Carbon\Carbon::parse($header->created_at)->format('d M Y') }}</td>
            </tr>
        </table>
    </div>

        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('stocktaking.report.export', $header->id) }}" class="btn btn-outline-success">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="card card-body border-0 shadow table-wrapper table-responsive">
        <table class="table table-hover">
        <thead>
            <tr>
                <th wire:click="sortBy('item_code')">Item Code</th>
                <th wire:click="sortBy('item_name')">Item Name</th>
                <th wire:click="sortBy('stock_aktual')">Actual Stock GDTP</th>
                <th wire:click="sortBy('stock_line')">Actual Stock Line</th>
                <th wire:click="sortBy('total_stock')">Total Stock</th>
                <th wire:click="sortBy('unit')">Unit</th>
            </tr>
                </thead>
                <tbody>
                    @foreach ($details as $item)
                        <tr>
                            <td>{{ $item['item_code'] }}</td>
                            <td>{{ $item['item_name'] }}</td>
                            <td>{{ $item['stock_aktual'] }}</td>
                            <td>{{ $item['stock_line'] }}</td>
                            <td>{{ $item['stock_aktual'] + $item['stock_line'] }}</td>
                            <td>{{ $item['unit'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
        </table>
    </div>
</div>

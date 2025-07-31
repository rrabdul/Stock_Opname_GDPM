<div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <h2 class="h4">Stock Taking Report</h2>
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
                    <th>Judul</th>
                    <th>Area</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->title }}</td>
                        <td>{{ $report->area }}</td>
                        <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d M Y') }}</td>
                        <td><span class="badge bg-success">{{ $report->status }}</span></td>
                        <td>
                            <a href="{{ route('stocktaking.reportdetail', $report->id) }}" class="btn btn-sm btn-primary">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

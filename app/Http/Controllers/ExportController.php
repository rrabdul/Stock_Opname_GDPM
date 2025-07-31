<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\TransactionInExport;
use App\Exports\TransactionOutExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportTransactionIn(Request $request)
    {
        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');

        return Excel::download(new TransactionInExport($dateFrom, $dateTo), 'transaksi_in.xlsx');

    }

    public function exportTransactionOut(Request $request)
    {
        $from = $request->query('from') ?? now()->subMonth()->format('Y-m-d');
        $to = $request->query('to') ?? now()->format('Y-m-d');

        return Excel::download(
            new TransactionOutExport($from, $to),
            'transaction_out_' . $from . '_to_' . $to . '.xlsx'
        );
    }
}

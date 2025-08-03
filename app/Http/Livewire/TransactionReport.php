<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TransactionIn;
use App\Models\TransactionOut;
use App\Models\TransactionReturn;

class TransactionReport extends Component
{
    public $dateFrom;
    public $dateTo;

    public function render()
    {
        $in = TransactionIn::query();
        $out = TransactionOut::query();
        $return = TransactionReturn::query();

        if ($this->dateFrom && $this->dateTo) {
            $in->whereBetween('created_at', [$this->dateFrom, $this->dateTo]);
            $out->whereBetween('created_at', [$this->dateFrom, $this->dateTo]);
            $return->whereBetween('created_at', [$this->dateFrom, $this->dateTo]);
        }

        return view('transaction.report', [
            'inTransactions' => $in->latest()->get(),
            'outTransactions' => $out->latest()->get(),
            'returnTransactions' => $return->latest()->get(),
        ]);
    }
}

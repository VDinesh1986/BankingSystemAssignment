<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavingAccount;
use App\Models\FundTransfer;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function history()
    {
        $accounts = auth()->user()->savingAccounts;
        $defaultAccountId = $accounts->first()?->id;

        $transactions = $this->getTransactionsForAccount($defaultAccountId);

        return view('transactions.history', compact('transactions', 'accounts', 'defaultAccountId'));
    }

    public function historyAjax(Request $request)
    {
        $accountId = $request->account_id;

        $transactions = $this->getTransactionsForAccount($accountId);

        return view('transactions.partials.historyTable', compact('transactions', 'accountId'))->render();
    }

    private function getTransactionsForAccount($accountId)
    {
        return FundTransfer::where('from_account_id', $accountId)
            ->orWhere('to_account_id', $accountId)
            ->with(['fromAccount.user', 'toAccount.user'])
            ->latest()
            ->get();
    }
}

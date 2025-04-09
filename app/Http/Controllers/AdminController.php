<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FundTransfer;
use App\Models\SavingAccount;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::where('role', 'user')->count();
        $accounts = SavingAccount::count();
        $transactions = FundTransfer::count();
        $totalBalance = SavingAccount::sum('balance');
        return view('admin.dashboard', compact('users', 'accounts', 'transactions', 'totalBalance'));
    }

    public function users()
    {
        $users = User::select('id', 'name', 'email')->withCount('savingAccounts')->where('role','user')->get();
        return view('admin.users.index', compact('users'));
    }

    public function accountList()
    {
        $accounts = SavingAccount::with('user')->get();
        return view('accounts.index', compact('accounts'));
    }

}

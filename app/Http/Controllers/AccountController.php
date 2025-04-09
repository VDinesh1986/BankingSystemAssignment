<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SavingAccount;

class AccountController extends Controller
{
     public function myAccounts()
    {
        $accounts = auth()->user()->savingAccounts;
        return view('accounts.myAccounts', compact('accounts'));
    }

}

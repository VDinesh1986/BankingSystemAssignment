<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavingAccount;
use App\Models\FundTransfer;
use Illuminate\Support\Facades\Http;
use DB;

class FundTransferController extends Controller
{
    public function create()
    {
        $accounts = auth()->user()->savingAccounts;
        return view('transactions.transferForm', compact('accounts'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'from_account_number' => 'required|exists:saving_accounts,account_number|different:to_account_number',
            'to_account_number' => 'required|exists:saving_accounts,account_number',
            'amount' => 'required|numeric|min:1',
            'fund_description' => 'required|string|max:255',
            'currency' => 'required|string|in:USD,GBP,EUR',
        ]);
    
        $from = SavingAccount::where('account_number', $request->from_account_number)->first();
        $to = SavingAccount::where('account_number', $request->to_account_number)->first();
    
        $amount = $request->amount;
        $fromCurrency = $from->currency;
        $toCurrency = $request->currency ?? 'USD'; // User selected or default
        $convertedAmount = $amount;
    
        //Handle currency conversion
        if ($fromCurrency !== $toCurrency) {
            $accessKey = config('services.exchange_rates.key');
    
            $response = Http::get('https://api.exchangeratesapi.io/v1/latest', [
                'access_key' => $accessKey,
                'symbols' => 'USD,GBP,EUR'
            ]);
    
            if ($response->successful()) {
                $rates = $response->json('rates');
    
                $fromRate = $rates[$fromCurrency] ?? null;
                $toRate = $rates[$toCurrency] ?? null;
    
                if (!$fromRate || !$toRate) {
                    return back()->withErrors([
                        'currency' => 'Currency rate not available. Please try again later.'
                    ]);
                }
    
                // Convert to EUR first, then to target currency
                $amountInEUR = $amount / $fromRate;
                $convertedAmount = $amountInEUR * $toRate;
    
                // Apply 0.01 spread & round
                $convertedAmount = round($convertedAmount - 0.01, 2);
            } else {
                return back()->withErrors([
                    'currency' => 'Currency conversion failed. Please check your API access or network.'
                ]);
            }
        }
    
        //Final balance check
        if ($from->balance < $amount) {
            return back()->withErrors([
                'amount' => "Insufficient funds. Your balance is {$from->balance} {$fromCurrency}, and you're trying to send {$amount} {$fromCurrency}."
            ]);
        }
    
        //Perform the transfer in a transaction
        DB::transaction(function () use ($from, $to, $amount, $toCurrency, $convertedAmount, $request) {
            $from->balance -= $amount;
            $to->balance += $convertedAmount;
    
            $from->save();
            $to->save();
    
            FundTransfer::create([
                'from_account_id' => $from->id,
                'to_account_id' => $to->id,
                'amount' => $amount,
                'converted_amount' => $convertedAmount,
                'currency' => $toCurrency,
                'from_account_balance' => $from->balance,
                'to_account_balance' => $to->balance,
                'description' => $request->fund_description,
            ]);
        });
    
        return redirect()->route('transactions.history')->with('success', 'Transfer completed successfully!');
    }
}

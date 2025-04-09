<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SavingAccount;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SavingAccountController extends Controller
{
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'user_id'     => 'required|exists:users,id',
            'accounts.*.first_name'  => 'required|string|max:255',
            'accounts.*.last_name'   => 'required|string|max:255',
            'accounts.*.dob'         => 'required|date',
            'accounts.*.address'     => 'required|string',
        ]);
       
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        try {
            foreach ($request->accounts as $accountData) {
                $accountNumber = '4510' . mt_rand(10000000, 99999999);
                SavingAccount::create([
                    'user_id'        => $request->user_id,
                    'first_name'     => $accountData['first_name'],
                    'last_name'      => $accountData['last_name'],
                    'dob'            => $accountData['dob'],
                    'address'        => $accountData['address'],
                    'account_number' => $accountNumber,
                    'balance'        => 10000.00,
                    'currency'        => $accountData['currency'],
                ]);
            }

            return response()->json(['message' => 'Saving accounts created successfully.']);
        } catch (\Exception $e) {
            return $e;
            return response()->json(['errors' => ['server' => ['An error occurred while creating accounts.']]], 500);
        }
    }

}

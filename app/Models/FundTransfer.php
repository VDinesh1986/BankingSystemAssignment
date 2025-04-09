<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SavingAccount;

class FundTransfer extends Model
{
    use HasFactory;
    protected $fillable = [
        'from_account_id',
        'to_account_id',
        'amount',
        'converted_amount',
        'currency',
        'from_account_balance',
        'to_account_balance',
        'description',
    ];

    public function fromAccount()
    {
        return $this->belongsTo(SavingAccount::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(SavingAccount::class, 'to_account_id');
    }
}

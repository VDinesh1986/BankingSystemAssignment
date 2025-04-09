<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'dob',
        'address',
        'account_number',
        'balance',
        'currency',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sentTransfers() {
        return $this->hasMany(FundTransfer::class, 'from_account_id');
    }
    
    public function receivedTransfers() {
        return $this->hasMany(FundTransfer::class, 'to_account_id');
    }
}

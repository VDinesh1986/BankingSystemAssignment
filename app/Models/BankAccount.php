<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'account_number', 'currency', 'balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionsFrom()
    {
        return $this->hasMany(Transaction::class, 'from_account_id');
    }

    public function transactionsTo()
    {
        return $this->hasMany(Transaction::class, 'to_account_id');
    }

}

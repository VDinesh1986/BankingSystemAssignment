@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Transfer Funds</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('transfer.store') }}">
        @csrf

        <div class="mb-3">
            <label for="currency">Transfer Currency</label>
            <select name="currency" class="form-select @error('currency') is-invalid @enderror">
                <option value="">Select Currency</option>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
                <option value="GBP">GBP</option>
            </select>
            @error('currency')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="from_account_number">From Account Number</label>
            <select id="from_account_number" name="from_account_number" class="form-select @error('from_account_number') is-invalid @enderror">
                <option value="">Select</option>
                @foreach ($accounts as $account)
                    <option value="{{ $account->account_number }}">{{ $account->account_number }} ({{ $account->currency }})</option>
                @endforeach
            </select>
            @error('from_account_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror            
        </div>

        <div class="mb-3">
            <label for="to_account_number">To Account Number</label>
            <input type="text" name="to_account_number" class="form-control @error('to_account_number') is-invalid @enderror">
            @error('to_account_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="amount">Amount</label>
            <input type="number" name="amount" step="0.01" class="form-control @error('amount') is-invalid @enderror">
            @error('amount')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="fund_description">Description</label>
            <input type="text" name="fund_description" step="0.01" class="form-control @error('fund_description') is-invalid @enderror">
            @error('fund_description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Transfer</button>
    </form>
</div>
@endsection

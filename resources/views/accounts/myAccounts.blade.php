@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">My Bank Accounts</h2>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Account Number</th>
                <th>Currency</th>
                <th>Balance</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $account)
                <tr>
                    <td>{{ $account->account_number }}</td>
                    <td>{{ $account->currency }}</td>
                    <td>{{ number_format($account->balance, 2) }}</td>
                    <td>{{ $account->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No accounts found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

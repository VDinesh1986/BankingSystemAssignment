@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">User Accounts</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered" id="userAccountsTable">    
        <thead>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Account Number</th>
            <th>Currency</th>
            <th>Balance</th>
        </tr>
        </thead>
        <tbody>
        @forelse($accounts as $index => $account)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $account->user->name }}</td>
                <td>{{ $account->account_number }}</td>
                <td>{{ $account->currency }}</td>
                <td>{{ number_format($account->balance, 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No users found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#userAccountsTable').DataTable();
    });
</script>
@endpush
@endsection



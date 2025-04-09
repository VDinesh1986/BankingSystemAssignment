@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Transaction History</h2>

    <div class="mb-3">
        <label for="accountSelector">Select Account</label>
        <select id="accountSelector" class="form-select">
            <option>Select</option>
            @foreach ($accounts as $account)
                <option value="{{ $account->id }}">{{ $account->account_number }}</option>
            @endforeach
        </select>
    </div>

    <div id="transactionTableArea">
        @include('transactions.partials.historyTable', ['transactions' => $transactions, 'accountId' => $defaultAccountId])
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#accountSelector').on('change', function () {
        const accountId = $(this).val();
        $.ajax({
            url: "{{ route('transactions.history.ajax') }}",
            method: 'GET',
            data: { account_id: accountId },
            success: function (data) {
                $('#transactionTableArea').html(data);
            },
            error: function () {
                alert('Failed to load transactions.');
            }
        });
    });
</script>
@endpush
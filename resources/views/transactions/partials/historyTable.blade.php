<table class="table table-striped">
    <thead>
        <tr>
            <th>Type</th>
            <th>From</th>
            <th>To</th>
            <th>Amount</th>
            <th>Converted</th>
            <th>Currency</th>
            <th>Balance After</th>
            <th>Date</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $tx)
            @php
                $isCredit = $tx->to_account_id == $accountId;
                $isDebit = $tx->from_account_id == $accountId;
            @endphp
            <tr>
                <td>
                    @if ($isCredit)
                        <span class="text-success">Credit</span>
                    @elseif ($isDebit)
                        <span class="text-danger">Debit</span>
                    @endif
                </td>
                <td>{{ $tx->fromAccount->account_number }} ({{ $tx->fromAccount->user->name }})</td>
                <td>{{ $tx->toAccount->account_number }} ({{ $tx->toAccount->user->name }})</td>
                <td>{{ number_format($tx->amount, 2) }}</td>
                <td>{{ $tx->converted_amount ? number_format($tx->converted_amount, 2) : '-' }}</td>
                <td>{{ $tx->currency }}</td>
                <td>
                    @if ($isCredit)
                        {{ number_format($tx->to_account_balance, 2) }}
                    @elseif ($isDebit)
                        {{ number_format($tx->from_account_balance, 2) }}
                    @endif
                </td>
                <td>{{ $tx->created_at->format('d M Y H:i') }}</td>
                <td>{{ $tx->description }}</td>
            </tr>
        @empty
            <tr><td colspan="9">No transactions found.</td></tr>
        @endforelse
    </tbody>
</table>

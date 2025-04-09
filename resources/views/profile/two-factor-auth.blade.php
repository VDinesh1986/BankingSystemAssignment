<!-- resources/views/profile/two-factor-auth.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Two-Factor Authentication (2FA)</h3>

    @if (! auth()->user()->two_factor_secret)
        <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Enable 2FA</button>
        </form>
    @else
        <p><strong>2FA is Enabled</strong></p>

        <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Disable 2FA</button>
        </form>

        <form method="POST" action="{{ url('/user/two-factor-recovery-codes') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-secondary">Regenerate Recovery Codes</button>
        </form>

        <div class="mt-3">
            <h5>Recovery Codes</h5>
            <ul>
                @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes, true)) as $code)
                    <li>{{ $code }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection

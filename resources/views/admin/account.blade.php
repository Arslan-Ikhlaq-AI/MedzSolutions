@extends('admin.layouts.app')

@section('title', 'Change password')
@section('heading', 'Account')

@section('content')
    <div class="page-head">
        <div>
            <h2>Your account</h2>
            <p class="muted">Signed in as {{ auth()->user()->email }}</p>
        </div>
    </div>

    <form class="card narrow" method="post" action="{{ route('admin.account.update') }}">
        @csrf
        @method('PUT')
        <h3>Change password</h3>
        <p class="muted">Use at least 10 characters with upper and lower case letters and a number.</p>

        <label class="field">
            <span class="field-label">Current password</span>
            <input type="password" name="current_password" autocomplete="current-password" required>
            @error('current_password')<span class="field-error">{{ $message }}</span>@enderror
        </label>
        <label class="field">
            <span class="field-label">New password</span>
            <input type="password" name="password" autocomplete="new-password" required>
            @error('password')<span class="field-error">{{ $message }}</span>@enderror
        </label>
        <label class="field">
            <span class="field-label">Confirm new password</span>
            <input type="password" name="password_confirmation" autocomplete="new-password" required>
        </label>
        <button type="submit" class="btn btn-primary">Update password</button>
    </form>
@endsection

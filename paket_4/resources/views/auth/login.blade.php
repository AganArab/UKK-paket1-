@extends('layouts.app')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h2>Login</h2>
        <form action="/login" method="POST" class="auth-form">
            @csrf
            <label>
                <span>Email</span>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </label>
            <label>
                <span>Password</span>
                <input type="password" name="password" required>
            </label>
            <button type="submit" class="button primary">Login</button>
            <a href="/register" class="button secondary">Register</a>
        </form>
    </div>
</div>
@endsection

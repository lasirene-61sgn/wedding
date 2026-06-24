@extends('layouts.guest_ui')

@section('title', 'Guest Login')

@push('styles')
<style>
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
    }
    .login-box {
        width: 100%;
        max-width: 450px;
        padding: 40px;
        text-align: center;
        position: relative;
    }
    .login-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(to right, var(--gold), var(--gold-dark));
        border-radius: 24px 24px 0 0;
    }
    .login-box h2 {
        font-family: 'Great Vibes', cursive;
        color: var(--gold);
        font-size: 3rem;
        margin-bottom: 5px;
        line-height: 1.2;
    }
    .login-box p.subtitle {
        color: var(--gray);
        font-size: 0.95rem;
        margin-bottom: 30px;
    }
    .form-group {
        margin-bottom: 20px;
        text-align: left;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--dark);
        font-weight: 500;
        font-size: 0.9rem;
    }
    .btn-submit {
        width: 100%;
        margin-top: 15px;
    }
    .error-message {
        background-color: #fff0f0;
        color: #d63031;
        padding: 12px;
        border-radius: 12px;
        margin-top: 20px;
        font-size: 0.9rem;
        border: 1px solid #ffcccc;
    }
</style>
@endpush

@section('content')
<div class="login-container">
    <div class="glass-panel login-box">
        <h2>Welcome</h2>
        <p class="subtitle">Please enter your details to view the invitation</p>
        
        <form action="{{ route('guest.login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="phone">Registered Number / Email</label>
                <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter Phone or Email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" required>
            </div>

            <button type="submit" class="btn-primary-wedding btn-submit">View Invitation</button>
        </form>
        
        @if(session('error'))
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Login - SIAP PKL')

@section('content')
<div class="auth-container">
    <div class="card auth-card">
        <div class="text-center mb-4">
            <h2 class="text-primary font-bold">SIAP PKL</h2>
            <p class="text-secondary mt-1">Kecamatan Cikampek</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="login" class="form-label">Email atau NIS/NIM</label>
                <div class="d-flex align-center" style="position:relative;">
                    <i class="ph ph-user" style="position:absolute; left: 1rem; color: var(--secondary);"></i>
                    <input type="text" id="login" name="login" class="form-control" style="padding-left: 2.5rem;" required autofocus value="{{ old('login') }}">
                </div>
                @error('login')
                    <div class="text-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="d-flex align-center" style="position:relative;">
                    <i class="ph ph-lock" style="position:absolute; left: 1rem; color: var(--secondary);"></i>
                    <input type="password" id="password" name="password" class="form-control" style="padding-left: 2.5rem;" required>
                </div>
                @error('password')
                    <div class="text-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <span>Masuk</span>
                <i class="ph ph-sign-in"></i>
            </button>

            <div class="text-center mt-4">
                <p class="text-secondary" style="font-size: 0.875rem;">
                    Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-primary">Daftar sekarang</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection

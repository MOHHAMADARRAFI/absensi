@extends('layouts.app')

@section('title', 'Login - SIAP PKL')

@section('content')
<div class="auth-wrapper d-flex" style="flex-direction: column; align-items: center; justify-content: center; padding: 2rem;">
    <!-- Branding -->
    <div class="auth-brand text-center" style="z-index: 1; margin-bottom: 2rem;">
        <div class="brand-icon" style="margin: 0 auto 1.5rem auto;">
            <i class="ph ph-student"></i>
        </div>
        <h1 class="text-white">SIAP PKL</h1>
        <p class="subtitle">Sistem Informasi Absensi & Penilaian PKL</p>
    </div>

    <!-- Form -->
    <div class="auth-right w-100" style="padding: 0; background: transparent;">
        <div class="auth-form-card" style="margin: 0 auto;">
            <div class="text-center mb-4">
                <h2 class="text-dark font-bold" style="font-size: 1.75rem;">Selamat Datang! 👋</h2>
                <p class="text-secondary mt-1">Masuk untuk melanjutkan aktivitas PKL-mu</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group mb-3">
                    <label for="login" class="form-label">Email atau NIS/NIM</label>
                    <div class="input-with-icon">
                        <i class="ph ph-user"></i>
                        <input type="text" id="login" name="login" class="form-control" placeholder="Masukkan Email atau NIS/NIM" required autofocus value="{{ old('login') }}">
                    </div>
                    @error('login')
                        <div class="text-error mt-1"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-with-icon">
                        <i class="ph ph-lock"></i>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                    </div>
                    @error('password')
                        <div class="text-error mt-1"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg auth-btn">
                    <span>Masuk Sekarang</span>
                    <i class="ph ph-arrow-right font-bold"></i>
                </button>

                <div class="text-center mt-4">
                    <p class="text-secondary" style="font-size: 0.9rem;">
                        Belum punya akun PKL? <br>
                        <a href="{{ route('register') }}" class="font-bold text-primary hover-underline mt-2 d-inline-block">Daftar sekarang</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    
    <div class="auth-footer-text text-center mt-4" style="position: static; z-index: 1;">
        &copy; {{ date('Y') }} Kecamatan Cikampek. Dibuat untuk Pelajar PKL.
    </div>
</div>
@endsection

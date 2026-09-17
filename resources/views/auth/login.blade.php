@extends('layouts.app')

@section('title', 'Login - SIAP PKL')

@section('content')
<div class="auth-wrapper" style="position: relative; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background: url('{{ asset('img/baackground-login.png') }}') center/cover no-repeat, linear-gradient(135deg, #064E33 0%, #032A1C 100%); font-family: 'Inter', sans-serif;">
    
    <!-- Branding -->
    <div class="auth-brand text-center" style="z-index: 10; margin-bottom: 2rem;">
        <div style="background-color: #1AC073; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
            <i class="ph ph-graduation-cap" style="color: white; font-size: 24px;"></i>
        </div>
        <h1 style="color: #ffffff; font-weight: 700; font-size: 2rem; margin-bottom: 0.25rem;">SIAP PKL</h1>
        <p class="subtitle" style="color: rgba(255, 255, 255, 0.8); font-size: 0.95rem; font-weight: 400;">Sistem Informasi Absensi & Penilaian PKL</p>
    </div>

    <!-- Form -->
    <div class="w-100" style="padding: 0 1rem; z-index: 10; display: flex; justify-content: center;">
        <div class="auth-form-card" style="width: 100%; max-width: 420px; background: #022516; padding: 2.5rem 2rem; border-radius: 1.5rem; border: 1px solid #144930; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);">
            <div class="mb-4 text-center" style="text-align: left !important;">
                <h2 style="color: #ffffff; font-weight: 700; font-size: 1.35rem; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">Selamat Datang! <span style="font-size: 1.35rem;">👋</span></h2>
                <p style="color: rgba(255, 255, 255, 0.5); font-size: 0.85rem; margin-bottom: 1.5rem;">Masuk untuk melanjutkan aktivitas PKL - mu</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group mb-3">
                    <label for="login" style="display: block; font-size: 0.85rem; font-weight: 500; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.5rem;">Email atau NIS/NIM</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ph ph-envelope" style="position: absolute; left: 1rem; color: #1AC073; font-size: 1.25rem; z-index: 10;"></i>
                        <input type="text" id="login" name="login" placeholder="Masukkan Email atau NIS/NIM" required autofocus value="{{ old('login') }}" 
                            style="width: 100%; padding: 0.875rem 1rem 0.875rem 3rem; border-radius: 12px; border: 1px solid #144930; background: #011C10; color: #ffffff; font-size: 0.9rem; outline: none; transition: all 0.3s;">
                    </div>
                    @error('login')
                        <div style="color: #F87171; font-size: 0.8rem; margin-top: 0.25rem;"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="password" style="display: block; font-size: 0.85rem; font-weight: 500; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.5rem;">Password</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ph ph-lock" style="position: absolute; left: 1rem; color: #1AC073; font-size: 1.25rem; z-index: 10;"></i>
                        <input type="password" id="password" name="password" placeholder="Masukkan Password" required 
                            style="width: 100%; padding: 0.875rem 3rem 0.875rem 3rem; border-radius: 12px; border: 1px solid #144930; background: #011C10; color: #ffffff; font-size: 0.9rem; outline: none; transition: all 0.3s;">
                        <i class="ph ph-eye-slash" id="togglePassword" style="position: absolute; right: 1rem; color: rgba(255, 255, 255, 0.5); font-size: 1.25rem; z-index: 10; cursor: pointer;"></i>
                    </div>
                    @error('password')
                        <div style="color: #F87171; font-size: 0.8rem; margin-top: 0.25rem;"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" style="width: 100%; background: #10B981; color: white; padding: 0.875rem 1rem; border-radius: 12px; font-size: 0.95rem; font-weight: 600; border: none; display: flex; justify-content: center; align-items: center; gap: 0.5rem; cursor: pointer; transition: all 0.3s; margin-top: 1.5rem;">
                    <span>Masuk Sekarang</span>
                    <i class="ph ph-arrow-right" style="font-size: 1.1rem; margin-left: auto; margin-right: 0;"></i>
                </button>
                <style>
                    button[type="submit"] {
                        justify-content: space-between !important;
                    }
                    button[type="submit"] span {
                        margin-left: auto;
                        margin-right: auto;
                        transform: translateX(10px);
                    }
                </style>

                <div class="text-center mt-4">
                    <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem;">
                        Belum punya akun PKL? <br>
                        <a href="{{ route('register') }}" style="color: #10B981; font-weight: 500; text-decoration: none; display: inline-block; margin-top: 0.25rem;">Daftar sekarang</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    
    <div class="text-center mt-4" style="position: relative; z-index: 10; color: rgba(255, 255, 255, 0.4); font-size: 0.8rem;">
        &copy; 2026 Kecamatan Cikampek. Dibuat untuk Pelajar PKL.
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const passwordInput = document.getElementById('password');
        const icon = e.target;
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('ph-eye-slash');
            icon.classList.add('ph-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('ph-eye');
            icon.classList.add('ph-eye-slash');
        }
    });
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    
    input::placeholder {
        color: rgba(255, 255, 255, 0.3);
    }
    input:focus {
        border-color: #10B981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
    }
    button:hover {
        background: #0FA472 !important;
        transform: translateY(-1px);
    }
    a:hover {
        text-decoration: underline !important;
    }
</style>
@endsection

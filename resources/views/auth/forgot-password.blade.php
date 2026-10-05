@extends('layouts.app')

@section('title', 'Lupa Password - SIAP PKL')

@section('content')
<div class="auth-wrapper" style="position: relative; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background: url('{{ asset('img/baackground-login.png') }}') center/cover no-repeat, linear-gradient(135deg, #064E33 0%, #032A1C 100%); font-family: 'Inter', sans-serif;">
    
    <!-- Branding -->
    <div class="auth-brand text-center" style="z-index: 10; margin-bottom: 2rem;">
        <h1 style="color: #ffffff; font-weight: 700; font-size: 2rem; margin-bottom: 0.25rem;">SIAP PKL</h1>
        <p class="subtitle" style="color: rgba(255, 255, 255, 0.8); font-size: 0.95rem; font-weight: 400;">Sistem Informasi Absensi & Penilaian PKL</p>
    </div>

    <!-- Form -->
    <div class="w-100" style="padding: 0 1rem; z-index: 10; display: flex; justify-content: center;">
        <div class="auth-form-card" style="width: 100%; max-width: 420px; background: #022516; padding: 2.5rem 2rem; border-radius: 1.5rem; border: 1px solid #144930; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);">
            <div class="mb-4 text-center" style="text-align: left !important;">
                <h2 style="color: #ffffff; font-weight: 700; font-size: 1.35rem; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">Lupa Password <span style="font-size: 1.35rem;">🔐</span></h2>
                <p style="color: rgba(255, 255, 255, 0.5); font-size: 0.85rem; margin-bottom: 1.5rem;">Masukkan Email atau NIS/NIM beserta password baru Anda</p>
            </div>

            <form method="POST" action="{{ route('forgot-password.process') }}">
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

                <div class="form-group mb-3">
                    <label for="password" style="display: block; font-size: 0.85rem; font-weight: 500; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.5rem;">Password Baru</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ph ph-lock" style="position: absolute; left: 1rem; color: #1AC073; font-size: 1.25rem; z-index: 10;"></i>
                        <input type="password" id="password" name="password" placeholder="Masukkan Password Baru" required 
                            style="width: 100%; padding: 0.875rem 3rem 0.875rem 3rem; border-radius: 12px; border: 1px solid #144930; background: #011C10; color: #ffffff; font-size: 0.9rem; outline: none; transition: all 0.3s;">
                        <i class="ph ph-eye-slash toggle-password" data-target="password" style="position: absolute; right: 1rem; color: rgba(255, 255, 255, 0.5); font-size: 1.25rem; z-index: 10; cursor: pointer;"></i>
                    </div>
                    @error('password')
                        <div style="color: #F87171; font-size: 0.8rem; margin-top: 0.25rem;"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="password_confirmation" style="display: block; font-size: 0.85rem; font-weight: 500; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.5rem;">Konfirmasi Password Baru</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ph ph-lock-key" style="position: absolute; left: 1rem; color: #1AC073; font-size: 1.25rem; z-index: 10;"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi Password Baru" required 
                            style="width: 100%; padding: 0.875rem 3rem 0.875rem 3rem; border-radius: 12px; border: 1px solid #144930; background: #011C10; color: #ffffff; font-size: 0.9rem; outline: none; transition: all 0.3s;">
                        <i class="ph ph-eye-slash toggle-password" data-target="password_confirmation" style="position: absolute; right: 1rem; color: rgba(255, 255, 255, 0.5); font-size: 1.25rem; z-index: 10; cursor: pointer;"></i>
                    </div>
                </div>

                <button type="submit" style="width: 100%; position: relative; background: #10B981; color: white; padding: 0.875rem 1rem; border-radius: 12px; font-size: 0.95rem; font-weight: 600; border: none; display: flex; justify-content: center; align-items: center; cursor: pointer; transition: all 0.3s; margin-top: 1.5rem;">
                    <span>Simpan Password Baru</span>
                    <i class="ph ph-check-circle" style="position: absolute; right: 1.25rem; font-size: 1.2rem;"></i>
                </button>

                <div class="text-center mt-4">
                    <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem;">
                        Ingat password Anda? <br>
                        <a href="{{ route('login') }}" style="color: #10B981; font-weight: 500; text-decoration: none; display: inline-block; margin-top: 0.25rem;">Kembali ke Login</a>
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
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function (e) {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.remove('ph-eye-slash');
                this.classList.add('ph-eye');
            } else {
                passwordInput.type = 'password';
                this.classList.remove('ph-eye');
                this.classList.add('ph-eye-slash');
            }
        });
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

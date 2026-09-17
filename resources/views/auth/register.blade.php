@extends('layouts.app')

@section('title', 'Daftar - SIAP PKL')

@section('content')
<div class="auth-wrapper" style="position: relative; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background: url('{{ asset('img/baackground-login.png') }}') center/cover no-repeat, linear-gradient(135deg, #064E33 0%, #032A1C 100%); font-family: 'Inter', sans-serif; padding: 2rem 1rem; overflow-y: auto;">
    
    <!-- Branding -->
    <div class="auth-brand text-center" style="z-index: 10; margin-bottom: 1rem;">

        <h1 style="color: #ffffff; font-weight: 700; font-size: 1.75rem; margin-bottom: 0.15rem;">SIAP PKL</h1>
        <p class="subtitle" style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem; font-weight: 400; margin-bottom: 0;">Sistem Informasi Absensi & Penilaian PKL</p>
    </div>

    <!-- Form -->
    <div class="w-100" style="padding: 0; z-index: 10; display: flex; justify-content: center;">
        <div class="auth-form-card" style="width: 100%; max-width: 600px; background: #022516; padding: 2rem; border-radius: 1.25rem; border: 1px solid #144930; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);">
            <div class="mb-3 text-center" style="text-align: left !important;">
                <h2 style="color: #ffffff; font-weight: 700; font-size: 1.25rem; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.15rem;">Buat Akun Baru <span style="font-size: 1.25rem;">✨</span></h2>
                <p style="color: rgba(255, 255, 255, 0.5); font-size: 0.8rem; margin-bottom: 1rem;">Lengkapi data diri kamu di bawah ini</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="name" style="display: block; font-size: 0.8rem; font-weight: 500; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.4rem;">Nama Lengkap</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class="ph ph-user" style="position: absolute; left: 1rem; color: #1AC073; font-size: 1.15rem; z-index: 10;"></i>
                            <input type="text" id="name" name="name" placeholder="Masukkan Nama Lengkap" required autofocus value="{{ old('name') }}" 
                                style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border-radius: 10px; border: 1px solid #144930; background: #011C10; color: #ffffff; font-size: 0.85rem; outline: none; transition: all 0.3s;">
                        </div>
                        @error('name')
                            <div style="color: #F87171; font-size: 0.75rem; margin-top: 0.2rem;"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="nis_nim" style="display: block; font-size: 0.8rem; font-weight: 500; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.4rem;">NIS / NIM</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class="ph ph-identification-card" style="position: absolute; left: 1rem; color: #1AC073; font-size: 1.15rem; z-index: 10;"></i>
                            <input type="text" id="nis_nim" name="nis_nim" placeholder="Nomor Induk" required value="{{ old('nis_nim') }}" 
                                style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border-radius: 10px; border: 1px solid #144930; background: #011C10; color: #ffffff; font-size: 0.85rem; outline: none; transition: all 0.3s;">
                        </div>
                        @error('nis_nim')
                            <div style="color: #F87171; font-size: 0.75rem; margin-top: 0.2rem;"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="email" style="display: block; font-size: 0.8rem; font-weight: 500; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.4rem;">Email</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class="ph ph-envelope-simple" style="position: absolute; left: 1rem; color: #1AC073; font-size: 1.15rem; z-index: 10;"></i>
                            <input type="email" id="email" name="email" placeholder="Masukkan Email Aktif" required value="{{ old('email') }}" 
                                style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border-radius: 10px; border: 1px solid #144930; background: #011C10; color: #ffffff; font-size: 0.85rem; outline: none; transition: all 0.3s;">
                        </div>
                        @error('email')
                            <div style="color: #F87171; font-size: 0.75rem; margin-top: 0.2rem;"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="sekolah_universitas" style="display: block; font-size: 0.8rem; font-weight: 500; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.4rem;">Asal Sekolah / Kampus</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class="ph ph-buildings" style="position: absolute; left: 1rem; color: #1AC073; font-size: 1.15rem; z-index: 10;"></i>
                            <input type="text" id="sekolah_universitas" name="sekolah_universitas" placeholder="Contoh: SMKN 1" required value="{{ old('sekolah_universitas') }}" 
                                style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border-radius: 10px; border: 1px solid #144930; background: #011C10; color: #ffffff; font-size: 0.85rem; outline: none; transition: all 0.3s;">
                        </div>
                        @error('sekolah_universitas')
                            <div style="color: #F87171; font-size: 0.75rem; margin-top: 0.2rem;"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="password" style="display: block; font-size: 0.8rem; font-weight: 500; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.4rem;">Password</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class="ph ph-lock" style="position: absolute; left: 1rem; color: #1AC073; font-size: 1.15rem; z-index: 10;"></i>
                            <input type="password" id="password" name="password" placeholder="Buat Password" required 
                                style="width: 100%; padding: 0.75rem 2.5rem 0.75rem 2.75rem; border-radius: 10px; border: 1px solid #144930; background: #011C10; color: #ffffff; font-size: 0.85rem; outline: none; transition: all 0.3s;">
                            <i class="ph ph-eye-slash" id="togglePassword" style="position: absolute; right: 0.75rem; color: rgba(255, 255, 255, 0.5); font-size: 1.15rem; z-index: 10; cursor: pointer;"></i>
                        </div>
                        @error('password')
                            <div style="color: #F87171; font-size: 0.75rem; margin-top: 0.2rem;"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" style="display: block; font-size: 0.8rem; font-weight: 500; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.4rem;">Konfirmasi Password</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class="ph ph-lock-key" style="position: absolute; left: 1rem; color: #1AC073; font-size: 1.15rem; z-index: 10;"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi Password" required 
                                style="width: 100%; padding: 0.75rem 2.5rem 0.75rem 2.75rem; border-radius: 10px; border: 1px solid #144930; background: #011C10; color: #ffffff; font-size: 0.85rem; outline: none; transition: all 0.3s;">
                            <i class="ph ph-eye-slash" id="togglePasswordConf" style="position: absolute; right: 0.75rem; color: rgba(255, 255, 255, 0.5); font-size: 1.15rem; z-index: 10; cursor: pointer;"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" style="width: 100%; background: #10B981; color: white; padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.9rem; font-weight: 600; border: none; display: flex; justify-content: center; align-items: center; gap: 0.5rem; cursor: pointer; transition: all 0.3s; margin-top: 0.5rem;">
                    <span>Daftar Sekarang</span>
                    <i class="ph ph-user-plus" style="font-size: 1.05rem; margin-left: auto; margin-right: 0;"></i>
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

                <div class="text-center mt-3">
                    <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.8rem; margin-bottom: 0;">
                        Sudah punya akun PKL? <br>
                        <a href="{{ route('login') }}" style="color: #10B981; font-weight: 500; text-decoration: none; display: inline-block; margin-top: 0.2rem;">Masuk di sini</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    
    <div class="text-center mt-3" style="position: relative; z-index: 10; color: rgba(255, 255, 255, 0.4); font-size: 0.75rem;">
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

    document.getElementById('togglePasswordConf').addEventListener('click', function (e) {
        const passwordInput = document.getElementById('password_confirmation');
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

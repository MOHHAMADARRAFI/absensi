@extends('layouts.app')

@section('title', 'Daftar - SIAP PKL')

@section('content')
<div class="auth-wrapper dark-theme-wrapper" style="position: relative; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(rgba(255,255,255,0.15), rgba(255,255,255,0.15)), url('{{ asset('img/login_bg_dark.jpg') }}') center/cover no-repeat; overflow: hidden; padding: 2rem;">
    
    <!-- Branding -->
    <div class="auth-brand text-center" style="z-index: 10; margin-bottom: 2rem;">
        <h1 style="color: #ffffff; font-weight: 800; font-size: 2.5rem; margin-bottom: 0.25rem;">SIAP PKL</h1>
        <p class="subtitle" style="color: rgba(255, 255, 255, 0.8); font-size: 1rem; font-weight: 500;">Sistem Informasi Absensi & Penilaian PKL</p>
    </div>

    <!-- Form -->
    <div class="w-100" style="padding: 0; z-index: 10; display: flex; justify-content: center;">
        <div class="auth-form-card dark-form-card" style="width: 100%; max-width: 500px; background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px); padding: 2.5rem 2rem; border-radius: 1.5rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 1px 2px rgba(255, 255, 255, 0.3); border: 1px solid rgba(255, 255, 255, 0.2);">
            <div class="text-center mb-4">
                <h2 style="color: #ffffff; font-weight: 800; font-size: 1.5rem;">Buat Akun Baru ✨</h2>
                <p style="color: rgba(255, 255, 255, 0.7); margin-top: 0.25rem; font-size: 0.85rem;">Lengkapi data diri kamu di bawah ini</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-group mb-3">
                    <label for="name" style="display: block; font-size: 0.85rem; font-weight: 600; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.5rem;">Nama Lengkap</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ph ph-user" style="position: absolute; left: 1rem; color: rgba(255, 255, 255, 0.5); font-size: 1.25rem; z-index: 10;"></i>
                        <input type="text" id="name" name="name" placeholder="Masukkan Nama Lengkap" required autofocus value="{{ old('name') }}" 
                            style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.3); background: rgba(255, 255, 255, 0.15); color: #ffffff; font-size: 0.95rem; outline: none; transition: all 0.3s; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                    </div>
                    @error('name')
                        <div style="color: #F87171; font-size: 0.8rem; margin-top: 0.25rem;"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="email" style="display: block; font-size: 0.85rem; font-weight: 600; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.5rem;">Email</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ph ph-envelope-simple" style="position: absolute; left: 1rem; color: rgba(255, 255, 255, 0.5); font-size: 1.25rem; z-index: 10;"></i>
                        <input type="email" id="email" name="email" placeholder="Masukkan Email Aktif" required value="{{ old('email') }}" 
                            style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.3); background: rgba(255, 255, 255, 0.15); color: #ffffff; font-size: 0.95rem; outline: none; transition: all 0.3s; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                    </div>
                    @error('email')
                        <div style="color: #F87171; font-size: 0.8rem; margin-top: 0.25rem;"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="nis_nim" style="display: block; font-size: 0.85rem; font-weight: 600; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.5rem;">NIS / NIM</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ph ph-identification-card" style="position: absolute; left: 1rem; color: rgba(255, 255, 255, 0.5); font-size: 1.25rem; z-index: 10;"></i>
                        <input type="text" id="nis_nim" name="nis_nim" placeholder="Nomor Induk Siswa/Mahasiswa" required value="{{ old('nis_nim') }}" 
                            style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.3); background: rgba(255, 255, 255, 0.15); color: #ffffff; font-size: 0.95rem; outline: none; transition: all 0.3s; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                    </div>
                    @error('nis_nim')
                        <div style="color: #F87171; font-size: 0.8rem; margin-top: 0.25rem;"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="sekolah_universitas" style="display: block; font-size: 0.85rem; font-weight: 600; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.5rem;">Asal Sekolah / Universitas</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="ph ph-buildings" style="position: absolute; left: 1rem; color: rgba(255, 255, 255, 0.5); font-size: 1.25rem; z-index: 10;"></i>
                        <input type="text" id="sekolah_universitas" name="sekolah_universitas" placeholder="Contoh: SMK Negeri 1 Cikampek" required value="{{ old('sekolah_universitas') }}" 
                            style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.3); background: rgba(255, 255, 255, 0.15); color: #ffffff; font-size: 0.95rem; outline: none; transition: all 0.3s; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                    </div>
                    @error('sekolah_universitas')
                        <div style="color: #F87171; font-size: 0.8rem; margin-top: 0.25rem;"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-3">
                    <div class="form-group w-100 mb-4">
                        <label for="password" style="display: block; font-size: 0.85rem; font-weight: 600; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.5rem;">Password</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class="ph ph-lock" style="position: absolute; left: 1rem; color: rgba(255, 255, 255, 0.5); font-size: 1.25rem; z-index: 10;"></i>
                            <input type="password" id="password" name="password" placeholder="Buat Password" required 
                                style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.3); background: rgba(255, 255, 255, 0.15); color: #ffffff; font-size: 0.95rem; outline: none; transition: all 0.3s; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                        </div>
                        @error('password')
                            <div style="color: #F87171; font-size: 0.8rem; margin-top: 0.25rem;"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group w-100 mb-4">
                        <label for="password_confirmation" style="display: block; font-size: 0.85rem; font-weight: 600; color: rgba(255, 255, 255, 0.9); margin-bottom: 0.5rem;">Konfirmasi Password</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class="ph ph-lock-key" style="position: absolute; left: 1rem; color: rgba(255, 255, 255, 0.5); font-size: 1.25rem; z-index: 10;"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi Password" required 
                                style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.3); background: rgba(255, 255, 255, 0.15); color: #ffffff; font-size: 0.95rem; outline: none; transition: all 0.3s; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                        </div>
                    </div>
                </div>

                <button type="submit" style="width: 100%; background: linear-gradient(135deg, #0D9488 0%, #0F766E 100%); color: white; padding: 0.85rem 1rem; border-radius: 12px; font-size: 0.95rem; font-weight: 600; border: none; display: flex; justify-content: space-between; align-items: center; cursor: pointer; box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25); transition: all 0.3s;">
                    <span>Daftar Sekarang</span>
                    <i class="ph ph-user-plus font-bold" style="font-size: 1.2rem;"></i>
                </button>

                <div class="text-center mt-4">
                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">
                        Sudah punya akun PKL? <br>
                        <a href="{{ route('login') }}" style="color: #2DD4BF; font-weight: 700; text-decoration: none; display: inline-block; margin-top: 0.5rem;">Masuk di sini</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    
    <div class="text-center mt-4" style="position: relative; z-index: 10; color: rgba(255, 255, 255, 0.5); font-size: 0.85rem;">
        &copy; {{ date('Y') }} Kecamatan Cikampek. Dibuat untuk Pelajar PKL.
    </div>
</div>

<style>
    /* Dark theme input focus states */
    input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }
    input:focus {
        background: rgba(255, 255, 255, 0.25) !important;
        border-color: #2DD4BF !important;
        box-shadow: 0 0 0 4px rgba(45, 212, 191, 0.15), inset 0 2px 4px rgba(0,0,0,0.1) !important;
    }
    input:focus + i, div:focus-within > i.ph {
        color: #2DD4BF !important;
    }
    button:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(13, 148, 136, 0.4) !important;
    }
    a:hover {
        text-decoration: underline !important;
    }
</style>
@endsection

@extends('layouts.app')

@section('title', 'Daftar - SIAP PKL')

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
        <div class="auth-form-card" style="margin: 0 auto; max-width: 500px;">
            <div class="text-center mb-4">
                <h2 class="text-dark font-bold" style="font-size: 1.75rem;">Buat Akun Baru ✨</h2>
                <p class="text-secondary mt-1">Lengkapi data diri kamu di bawah ini</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <div class="input-with-icon">
                        <i class="ph ph-user"></i>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan Nama Lengkap" required autofocus value="{{ old('name') }}">
                    </div>
                    @error('name')
                        <div class="text-error mt-1"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-with-icon">
                        <i class="ph ph-envelope-simple"></i>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan Email Aktif" required value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <div class="text-error mt-1"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="nis_nim" class="form-label">NIS / NIM</label>
                    <div class="input-with-icon">
                        <i class="ph ph-identification-card"></i>
                        <input type="text" id="nis_nim" name="nis_nim" class="form-control" placeholder="Nomor Induk Siswa/Mahasiswa" required value="{{ old('nis_nim') }}">
                    </div>
                    @error('nis_nim')
                        <div class="text-error mt-1"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="sekolah_universitas" class="form-label">Asal Sekolah / Universitas</label>
                    <div class="input-with-icon">
                        <i class="ph ph-buildings"></i>
                        <input type="text" id="sekolah_universitas" name="sekolah_universitas" class="form-control" placeholder="Contoh: SMK Negeri 1 Cikampek" required value="{{ old('sekolah_universitas') }}">
                    </div>
                    @error('sekolah_universitas')
                        <div class="text-error mt-1"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-3">
                    <div class="form-group w-100 mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-with-icon">
                            <i class="ph ph-lock"></i>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Buat Password" required>
                        </div>
                        @error('password')
                            <div class="text-error mt-1"><i class="ph ph-warning-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group w-100 mb-4">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <div class="input-with-icon">
                            <i class="ph ph-lock-key"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi Password" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg auth-btn">
                    <span>Daftar Sekarang</span>
                    <i class="ph ph-user-plus font-bold"></i>
                </button>

                <div class="text-center mt-4">
                    <p class="text-secondary" style="font-size: 0.9rem;">
                        Sudah punya akun PKL? <br>
                        <a href="{{ route('login') }}" class="font-bold text-primary hover-underline mt-2 d-inline-block">Masuk di sini</a>
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

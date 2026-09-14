@extends('layouts.app')

@section('title', 'Daftar - SIAP PKL')

@section('content')
<div class="auth-container">
    <div class="card auth-card" style="max-width: 500px;">
        <div class="text-center mb-4">
            <h2 class="text-primary font-bold">Daftar Akun PKL</h2>
            <p class="text-secondary mt-1">SIAP PKL Kecamatan Cikampek</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="form-control" required autofocus value="{{ old('name') }}">
                @error('name')
                    <div class="text-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required value="{{ old('email') }}">
                @error('email')
                    <div class="text-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nis_nim" class="form-label">NIS / NIM</label>
                <input type="text" id="nis_nim" name="nis_nim" class="form-control" required value="{{ old('nis_nim') }}">
                @error('nis_nim')
                    <div class="text-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="sekolah_universitas" class="form-label">Asal Sekolah / Universitas</label>
                <input type="text" id="sekolah_universitas" name="sekolah_universitas" class="form-control" required value="{{ old('sekolah_universitas') }}">
                @error('sekolah_universitas')
                    <div class="text-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-4">
                <div class="form-group w-100 mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    @error('password')
                        <div class="text-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group w-100 mb-4">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <span>Daftar</span>
                <i class="ph ph-user-plus"></i>
            </button>

            <div class="text-center mt-4">
                <p class="text-secondary" style="font-size: 0.875rem;">
                    Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-primary">Masuk di sini</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection

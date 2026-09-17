@extends('layouts.app')

@section('title', 'Pengaturan - Admin SIAP PKL')

@section('content')
<div class="student-dashboard">
    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Main Content --}}
    <div class="student-main">
        <div class="student-topbar">
            <div style="display: flex; align-items: center;">
                <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="ph ph-list"></i>
                </button>
                <div>
                    <div class="student-eyebrow">Pengaturan</div>
                    <h1 style="font-size: 1.25rem; font-weight: 800; color: #1E293B; letter-spacing: -0.02em;">Pengaturan Sistem & Akun</h1>
                </div>
            </div>
        </div>

        <div class="student-content" style="max-width: 800px;">
            
            @if(session('success'))
                <div class="alert-success mb-4" style="padding: 1rem; border-radius: 8px; background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="ph ph-check-circle" style="font-size: 1.25rem;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
            <div class="alert-danger mb-4" style="padding: 1rem; border-radius: 8px; background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Pengaturan Sistem -->
            <div class="modern-card mb-4">
                <div style="margin-bottom: 1.5rem; border-bottom: 1px solid #E2E8F0; padding-bottom: 1rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: #0F766E; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="ph ph-map-pin"></i> Lokasi Presensi
                    </h3>
                    <p style="font-size: 0.875rem; color: #64748B; margin: 0.25rem 0 0 0;">Atur titik koordinat dan batas radius untuk fitur presensi masuk dan pulang.</p>
                </div>

                <form action="{{ route('admin.update_pengaturan') }}" method="POST">
                    @csrf
                    
                    <div class="form-group mb-4">
                        <label class="form-label font-semibold text-secondary">Nama Kantor / Instansi</label>
                        <input type="text" name="nama_kantor" class="form-control" value="{{ old('nama_kantor', $pengaturan->nama_kantor ?? '') }}" required placeholder="Contoh: Kantor Kecamatan Cikampek">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group mb-0">
                            <label class="form-label font-semibold text-secondary">Latitude</label>
                            <input type="text" name="latitude_kantor" class="form-control" value="{{ old('latitude_kantor', $pengaturan->latitude_kantor ?? '') }}" required placeholder="Contoh: -6.4025">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label font-semibold text-secondary">Longitude</label>
                            <input type="text" name="longitude_kantor" class="form-control" value="{{ old('longitude_kantor', $pengaturan->longitude_kantor ?? '') }}" required placeholder="Contoh: 107.4562">
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label font-semibold text-secondary">Radius Maksimal (Meter)</label>
                        <input type="number" name="radius_meter" class="form-control" value="{{ old('radius_meter', $pengaturan->radius_meter ?? 50) }}" required min="1">
                        <small style="color: #64748B;">Peserta hanya bisa melakukan presensi jika berada di dalam radius ini dari titik koordinat.</small>
                    </div>

                    <div class="d-flex justify-end pt-2">
                        <button type="submit" class="btn btn-primary" style="background: #0F766E; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="ph ph-floppy-disk"></i> Simpan Lokasi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Ganti Password -->
            <div class="modern-card">
                <div style="margin-bottom: 1.5rem; border-bottom: 1px solid #E2E8F0; padding-bottom: 1rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: #0F766E; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="ph ph-lock-key"></i> Ubah Password
                    </h3>
                    <p style="font-size: 0.875rem; color: #64748B; margin: 0.25rem 0 0 0;">Pastikan akun Anda menggunakan password yang kuat.</p>
                </div>

                <form action="{{ route('admin.update_password') }}" method="POST">
                    @csrf

                    <div class="form-group mb-4">
                        <label class="form-label font-semibold text-secondary">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group mb-0">
                            <label class="form-label font-semibold text-secondary">Password Baru</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label font-semibold text-secondary">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="d-flex justify-end pt-2">
                        <button type="submit" class="btn btn-primary" style="background: #0F766E; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="ph ph-key"></i> Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Kehadiran - Admin SIAP PKL')

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
                    <div class="student-eyebrow">Manajemen</div>
                    <h1 style="font-size: 1.25rem; font-weight: 800; color: #1E293B; letter-spacing: -0.02em;">Edit Kehadiran</h1>
                </div>
            </div>
            <div class="student-date">
                <a href="{{ route('admin.kehadiran') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
                    <i class="ph ph-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="student-content">
            <div class="card" style="max-width: 600px;">
                <div style="margin-bottom: 1.5rem; border-bottom: 1px solid #E2E8F0; padding-bottom: 1rem;">
                    <h3 class="font-bold">Informasi Peserta</h3>
                    <p style="margin: 0.5rem 0 0.25rem;"><strong>Nama:</strong> {{ $absen->user->name }}</p>
                    <p style="margin: 0;"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($absen->tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                </div>

                <form method="POST" action="{{ route('admin.kehadiran.update', $absen->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="hadir" {{ $absen->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="terlambat" {{ $absen->status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="izin" {{ $absen->status == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ $absen->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="tidak_hadir" {{ $absen->status == 'tidak_hadir' || $absen->status == 'alpa' ? 'selected' : '' }}>Tidak Hadir</option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group" style="flex: 1;">
                            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Jam Masuk</label>
                            <input type="time" name="jam_masuk" class="form-control" value="{{ $absen->jam_masuk ? date('H:i', strtotime($absen->jam_masuk)) : '' }}">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Jam Pulang</label>
                            <input type="time" name="jam_pulang" class="form-control" value="{{ $absen->jam_pulang ? date('H:i', strtotime($absen->jam_pulang)) : '' }}">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ $absen->keterangan }}</textarea>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

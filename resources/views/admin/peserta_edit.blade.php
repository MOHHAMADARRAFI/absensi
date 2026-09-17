@extends('layouts.app')

@section('title', 'Edit Peserta - Admin SIAP PKL')

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
                    <h1 style="font-size: 1.25rem; font-weight: 800; color: #1E293B; letter-spacing: -0.02em;">Edit Data Peserta</h1>
                </div>
            </div>
            <div class="student-date">
                <i class="ph ph-calendar-blank"></i>
                <span class="font-semibold">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
            </div>
        </div>

        <div class="student-content" style="max-width: 800px;">
            <div class="modern-card">
                @if ($errors->any())
                <div class="alert-danger mb-4" style="padding: 1rem; border-radius: 8px; background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B;">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.peserta.update', $peserta->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group mb-0">
                            <label class="form-label font-semibold text-secondary">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $peserta->name) }}" required>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label font-semibold text-secondary">NIS / NIM</label>
                            <input type="text" name="nis_nim" class="form-control" value="{{ old('nis_nim', $peserta->nis_nim) }}">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group mb-0">
                            <label class="form-label font-semibold text-secondary">Asal Instansi / Sekolah</label>
                            <input type="text" name="sekolah_universitas" class="form-control" value="{{ old('sekolah_universitas', $peserta->sekolah_universitas) }}">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label font-semibold text-secondary">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan', $peserta->jurusan) }}">
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label font-semibold text-secondary">Penempatan Divisi</label>
                        <input type="text" name="divisi" class="form-control" value="{{ old('divisi', $peserta->divisi) }}" placeholder="Contoh: IT, Administrasi, dll">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="form-group mb-0">
                            <label class="form-label font-semibold text-secondary">Tanggal Mulai PKL</label>
                            <input type="date" name="tgl_mulai" class="form-control" value="{{ old('tgl_mulai', $peserta->tgl_mulai) }}">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label font-semibold text-secondary">Tanggal Selesai PKL</label>
                            <input type="date" name="tgl_selesai" class="form-control" value="{{ old('tgl_selesai', $peserta->tgl_selesai) }}">
                        </div>
                    </div>

                    <div class="d-flex align-center justify-between pt-4" style="border-top: 1px solid #E2E8F0;">
                        <a href="{{ route('admin.peserta') }}" class="btn" style="background: #F1F5F9; color: #475569; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary" style="background: #0F766E; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="ph ph-floppy-disk"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Data Peserta - Admin SIAP PKL')

@section('content')
<div class="admin-layout">
    {{-- Sidebar --}}
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="ph ph-buildings"></i>
            <span>SIAP PKL</span>
        </div>
        <div class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item">
                <i class="ph ph-squares-four"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.peserta') }}" class="sidebar-item active">
                <i class="ph ph-users"></i>
                <span>Data Peserta</span>
            </a>
            <a href="{{ route('admin.pengajuan') }}" class="sidebar-item">
                <i class="ph ph-envelope-open"></i>
                <span>Pengajuan</span>
            </a>
            <a href="{{ route('admin.laporan') }}" class="sidebar-item">
                <i class="ph ph-file-text"></i>
                <span>Laporan</span>
            </a>
        </div>
        <div style="padding: 1rem;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline w-100"
                    style="justify-content: flex-start; border: none; color: var(--danger);">
                    <i class="ph ph-sign-out"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="admin-main">
        <div class="admin-header">
            <div>
                <h2 class="font-bold">Data Peserta PKL</h2>
                <p class="text-secondary" style="font-size: 0.875rem; margin-top: 2px;">
                    Total: {{ $peserta->count() }} peserta |
                    <span style="color: var(--success);">{{ $peserta->whereNotNull('face_descriptor')->count() }} terdaftar wajah</span>
                    @if($peserta->whereNull('face_descriptor')->count() > 0)
                    | <span style="color: var(--warning);">{{ $peserta->whereNull('face_descriptor')->count() }} belum terdaftar</span>
                    @endif
                </p>
            </div>
        </div>

        @if(session('success'))
        <div class="alert-success mb-4" style="padding: 0.875rem 1rem; border-radius: 8px; background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534; display: flex; align-items: center; gap: 0.5rem;">
            <i class="ph ph-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif

        <div class="admin-content">
            <div class="card" style="padding: 0; overflow: hidden;">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Peserta</th>
                                <th>Instansi / Jurusan</th>
                                <th>Divisi</th>
                                <th>Periode PKL</th>
                                <th>Status Wajah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peserta as $p)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.625rem;">
                                        <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #1D4ED8, #3B82F6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; flex-shrink: 0;">
                                            {{ substr($p->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold" style="font-size: 0.9rem;">{{ $p->name }}</div>
                                            <div class="text-secondary" style="font-size: 0.75rem;">{{ $p->nis_nim }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem;">{{ $p->sekolah_universitas }}</div>
                                    <div class="text-secondary" style="font-size: 0.75rem;">{{ $p->jurusan }}</div>
                                </td>
                                <td style="font-size: 0.875rem;">{{ $p->divisi ?? '-' }}</td>
                                <td>
                                    @if($p->tgl_mulai && $p->tgl_selesai)
                                    <div style="font-size: 0.8rem;">{{ date('d M Y', strtotime($p->tgl_mulai)) }}</div>
                                    <div style="font-size: 0.8rem; color: #64748B;">s/d {{ date('d M Y', strtotime($p->tgl_selesai)) }}</div>
                                    @else
                                    <span class="text-secondary" style="font-size: 0.8rem;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->face_descriptor)
                                        <span class="badge badge-success">Terdaftar</span>
                                    @else
                                        <span class="badge badge-warning">Belum Terdaftar</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status_aktif == 'aktif')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                                        <a href="{{ route('admin.peserta.registrasi_wajah', $p->id) }}"
                                            class="btn btn-primary"
                                            style="padding: 0.35rem 0.65rem; font-size: 0.78rem; gap: 0.3rem;">
                                            <i class="ph ph-scan-smiley"></i>
                                            {{ $p->face_descriptor ? 'Perbarui Wajah' : 'Daftarkan Wajah' }}
                                        </a>
                                        @if($p->face_descriptor)
                                        <form action="{{ route('admin.peserta.hapus_wajah', $p->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus data wajah {{ $p->name }}? Peserta tidak bisa presensi sampai wajah didaftarkan ulang.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn"
                                                style="padding: 0.35rem 0.65rem; font-size: 0.78rem; gap: 0.3rem; background: #FEF2F2; color: #ef4444; border: 1px solid #FCA5A5;">
                                                <i class="ph ph-trash"></i>
                                                Hapus Wajah
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-secondary" style="padding: 2.5rem;">
                                    <i class="ph ph-users" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                                    Belum ada data peserta PKL.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

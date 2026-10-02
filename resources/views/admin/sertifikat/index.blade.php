@extends('layouts.app')

@section('title', 'Sertifikat PKL - Admin SIAP PKL')

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
                    <div class="student-eyebrow">Sertifikat PKL</div>
                    <h1 style="font-size: 1.25rem; font-weight: 800; color: #1E293B; letter-spacing: -0.02em;">Daftar Sertifikat</h1>
                </div>
            </div>
            
            <a href="{{ route('admin.sertifikat.create') }}" class="btn btn-primary" style="background: #0F766E; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; color: white; text-decoration: none;">
                <i class="ph ph-plus"></i> Buat Sertifikat
            </a>
        </div>

        <div class="student-content">
            <div class="modern-card">
                <div class="table-responsive" style="overflow-x: auto; width: 100%; white-space: nowrap;">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Peserta</th>
                                <th>Sekolah / Jurusan</th>
                                <th>Nomor Sertifikat</th>
                                <th>Periode PKL</th>
                                <th>Hasil</th>
                                <th>Rata-rata</th>
                                <th>Tanggal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sertifikats as $index => $sertifikat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div style="font-weight: 600; color: #1E293B;">{{ $sertifikat->user->name ?? 'User Terhapus' }}</div>
                                </td>
                                <td>
                                    <div>{{ $sertifikat->user->sekolah_universitas ?? '-' }}</div>
                                    <div style="font-size: 0.75rem; color: #64748B;">{{ $sertifikat->user->jurusan ?? '-' }}</div>
                                </td>
                                <td>{{ $sertifikat->nomor_sertifikat }}</td>
                                <td>
                                    @if($sertifikat->user && $sertifikat->user->tgl_mulai)
                                        {{ \Carbon\Carbon::parse($sertifikat->user->tgl_mulai)->format('d M Y') }} - 
                                        {{ \Carbon\Carbon::parse($sertifikat->user->tgl_selesai)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: #ECFDF5; color: #059669;">
                                        {{ $sertifikat->hasil_pkl }}
                                    </span>
                                </td>
                                <td><strong>{{ number_format($sertifikat->nilai_rata_rata, 2) }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($sertifikat->tanggal_sertifikat)->format('d M Y') }}</td>
                                <td>
                                    <div class="action-buttons justify-end">
                                        <a href="{{ route('admin.sertifikat.show-pdf', $sertifikat->id) }}" target="_blank" class="btn-action btn-view" title="Lihat PDF">
                                            <i class="ph ph-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.sertifikat.pdf', $sertifikat->id) }}" class="btn-action btn-edit" title="Download PDF" style="background-color: #ECFDF5; color: #059669;">
                                            <i class="ph ph-download-simple"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center" style="padding: 3rem 1rem;">
                                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; color: #64748B;">
                                        <i class="ph ph-certificate" style="font-size: 3rem; margin-bottom: 1rem; color: #CBD5E1;"></i>
                                        <p style="font-weight: 500; margin: 0;">Belum ada sertifikat yang dibuat</p>
                                    </div>
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

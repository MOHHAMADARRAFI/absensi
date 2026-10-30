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
            @if(session('success'))
                <div class="alert-success mb-4" style="padding: 0.85rem 1rem; border-radius: 8px; background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem;">
                    <i class="ph ph-check-circle" style="font-size: 1.25rem;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-danger mb-4" style="padding: 0.85rem 1rem; border-radius: 8px; background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem;">
                    <i class="ph ph-warning-circle" style="font-size: 1.25rem;"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

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
                                    <div class="action-buttons justify-end" style="display: flex; align-items: center; justify-content: flex-end; gap: 0.4rem;">
                                        <a href="{{ route('admin.sertifikat.show-pdf', $sertifikat->id) }}" target="_blank" class="btn" title="Lihat PDF" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; text-decoration: none;">
                                            <i class="ph ph-eye" style="font-size: 1rem;"></i>
                                        </a>
                                        <a href="{{ route('admin.sertifikat.pdf', $sertifikat->id) }}" class="btn" title="Download PDF" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; background-color: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; text-decoration: none;">
                                            <i class="ph ph-download-simple" style="font-size: 1rem;"></i>
                                        </a>
                                        <form action="{{ route('admin.sertifikat.destroy', $sertifikat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sertifikat untuk {{ addslashes($sertifikat->user->name ?? 'peserta ini') }}?');" style="display: inline-block; margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn" title="Hapus Sertifikat" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; cursor: pointer;">
                                                <i class="ph ph-trash" style="font-size: 1rem;"></i>
                                            </button>
                                        </form>
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

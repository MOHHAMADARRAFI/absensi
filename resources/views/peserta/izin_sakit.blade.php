@extends('layouts.app')

@section('title', 'Pengajuan Izin / Sakit')

@section('content')
<div class="mobile-layout" style="padding-bottom: 2rem;">
    <div class="mobile-header" style="padding-bottom: 1.5rem; border-radius: 0;">
        <div class="d-flex align-center gap-4">
            <a href="{{ route('peserta.dashboard') }}" style="color: white; font-size: 1.5rem;"><i class="ph ph-arrow-left"></i></a>
            <h3 class="font-bold">Pengajuan Izin/Sakit</h3>
        </div>
    </div>

    <div class="mobile-content">
        <div class="card mt-4">
            <form action="{{ route('peserta.submit_izin_sakit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Jenis Pengajuan</label>
                    <select name="jenis" class="form-control" required>
                        <option value="izin" {{ request('type') == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ request('type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    </select>
                </div>

                <div class="d-flex gap-4">
                    <div class="form-group w-100">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group w-100">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" required min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alasan Singkat</label>
                    <input type="text" name="alasan" class="form-control" required placeholder="Cth: Keperluan keluarga">
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan Detail</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Jelaskan secara rinci..."></textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Bukti Dokumen (Opsional / Wajib jika Sakit)</label>
                    <input type="file" name="bukti_dokumen" class="form-control" accept="image/*,.pdf">
                    <small class="text-secondary mt-1 d-block">Format: JPG, PNG, PDF. Max 2MB.</small>
                </div>

                <button type="submit" class="btn btn-primary w-100">Kirim Pengajuan</button>
            </form>
        </div>
    </div>
</div>
@endsection

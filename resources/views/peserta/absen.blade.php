@extends('layouts.app')

@section('title', 'Form Presensi PKL')

@push('styles')
<style>
    @import url("https://unpkg.com/leaflet@1.9.4/dist/leaflet.css");
    /* Radio Pills */
    .keterangan-selector {
        display: flex;
        gap: 0.5rem;
        background: #F1F5F9;
        padding: 0.35rem;
        border-radius: var(--radius-md);
        margin-bottom: 1.5rem;
    }
    .keterangan-selector label {
        flex: 1;
        text-align: center;
        padding: 0.75rem 0;
        cursor: pointer;
        font-weight: 600;
        color: #64748B;
        border-radius: var(--radius-sm);
        transition: all 0.2s;
    }
    .keterangan-selector input[type="radio"] { display: none; }
    .keterangan-selector input[type="radio"]:checked + label {
        background: white;
        color: var(--primary);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    /* Webcam */
    .webcam-wrapper {
        position: relative;
        background: #0f172a;
        border-radius: 14px;
        overflow: hidden;
        aspect-ratio: 4/3;
        margin-bottom: 1.5rem;
    }
    #webcam {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
        transform: scaleX(-1);
    }
    #faceCanvas {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        pointer-events: none;
        transform: scaleX(-1);
    }
    .webcam-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.65));
        padding: 0.75rem 1rem;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .cam-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #ef4444; animation: blink 1s infinite;
    }
    .cam-dot.on { background: #22c55e; animation: none; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }
    
    .spin { animation: spin 1s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
<div class="student-dashboard">
    <!-- Sidebar -->
    @include('peserta.partials.sidebar')

    <!-- Main Content -->
    <div class="student-main">
        <div class="student-topbar">
            <div style="display: flex; align-items: center;">
                <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="ph ph-list"></i>
                </button>
                <div>
                    <div class="student-eyebrow">Aktivitas</div>
                    <h1>Kirim Presensi ({{ ucfirst($type) }})</h1>
                </div>
            </div>
            <div class="student-date">
                <i class="ph ph-calendar-blank"></i>
                <span class="font-semibold">{{ date('l, d F Y') }}</span>
            </div>
        </div>

        <div class="student-content" style="max-width: 800px;">
            <div class="modern-card">
                <div class="form-group">
                    <label class="form-label">NIS / NIM</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->nis_nim }}" readonly style="background-color: #F8FAFC; color: #475569;">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly style="background-color: #F8FAFC; color: #475569;">
                </div>

                <!-- Keterangan Selector -->
                <label class="form-label">Keterangan</label>
                <div class="keterangan-selector">
                    <input type="radio" id="ket_hadir" name="keterangan_type" value="hadir" checked onchange="toggleForm()">
                    <label for="ket_hadir">Hadir</label>

                    <input type="radio" id="ket_izin" name="keterangan_type" value="izin" onchange="toggleForm()">
                    <label for="ket_izin">Izin</label>
                    
                    <input type="radio" id="ket_sakit" name="keterangan_type" value="sakit" onchange="toggleForm()">
                    <label for="ket_sakit">Sakit</label>
                </div>

                <!-- FORM HADIR -->
                <form id="form-hadir" method="POST" action="{{ $type === 'masuk' ? route('absen.masuk') : route('absen.pulang') }}">
                    @csrf
                    <div class="form-group mt-4 pt-4" style="border-top: 1px dashed var(--border);">
                        @if($type === 'pulang')
                            <div class="form-group mb-4">
                                <label class="form-label mb-2">Laporan Kegiatan Hari Ini (Wajib)</label>
                                <textarea name="keterangan" class="form-control" rows="3" required placeholder="Tuliskan apa saja yang Anda kerjakan atau pelajari hari ini..."></textarea>
                            </div>
                        @endif

                        @if($type === 'masuk')
                            <label class="form-label mb-3 mt-2">Lokasi Anda (Wajib)</label>
                            <div id="map" style="height: 250px; border-radius: 14px; margin-bottom: 0.5rem; z-index: 1;"></div>
                            <div id="locationStatus" class="alert alert-warning mb-4" style="font-size: 0.85rem; padding: 0.75rem; border-radius: 8px;">
                                <i class="ph ph-spinner-gap spin"></i> Mendapatkan lokasi GPS...
                            </div>
                            
                            <input type="hidden" name="lat_masuk" id="lat_masuk">
                            <input type="hidden" name="long_masuk" id="long_masuk">
                            <input type="hidden" name="jarak_masuk" id="jarak_masuk">
                        @endif

                        <label class="form-label mb-3 mt-4">Foto Kehadiran (Wajib)</label>

                        <div class="webcam-wrapper">
                            <video id="webcam" autoplay playsinline muted></video>
                            <canvas id="faceCanvas" style="display: none;"></canvas>
                            <div class="webcam-overlay">
                                <div class="cam-dot" id="camDot"></div>
                                <span style="color:white; font-size: 0.8rem; font-weight: 600;" id="camLabel">Menyiapkan kamera...</span>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="foto" id="inputFoto">

                    <button type="button" id="btnSubmitAbsen" class="btn btn-primary w-100" style="padding: 1rem; font-size: 1.1rem; border-radius: var(--radius-lg); background: linear-gradient(135deg, #059669, #10B981); box-shadow: 0 4px 12px rgba(16,185,129,0.3); border: none; color: white;" disabled onclick="submitAbsen()">
                        <i class="ph ph-camera"></i>
                        <span>Ambil Foto & Kirim Presensi</span>
                    </button>
                </form>

                <!-- FORM IZIN/SAKIT -->
                <form id="form-izin-sakit" method="POST" action="{{ route('peserta.submit_izin_sakit') }}" enctype="multipart/form-data" style="display: none;">
                    @csrf
                    <input type="hidden" name="jenis" id="inputJenis" value="izin">
                    <input type="hidden" name="tanggal_mulai" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="tanggal_selesai" value="{{ date('Y-m-d') }}">

                    <div class="form-group mt-4 pt-4" style="border-top: 1px dashed var(--border);">
                        <label class="form-label">Alasan Singkat</label>
                        <input type="text" name="alasan" class="form-control" required placeholder="Cth: Keperluan keluarga / Sakit demam">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keterangan Detail (Opsional)</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Jelaskan secara rinci..."></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Bukti Dokumen (Opsional/Wajib untuk Sakit)</label>
                        <input type="file" name="bukti_dokumen" class="form-control" accept="image/*,.pdf">
                        <small class="text-secondary mt-1 d-block" style="font-size: 0.75rem;">Format: JPG, PNG, PDF. Max 2MB.</small>
                    </div>

                    <button type="submit" class="btn btn-warning w-100" style="padding: 1rem; font-size: 1.1rem; border-radius: var(--radius-lg); color: white;">
                        <i class="ph ph-paper-plane-tilt"></i>
                        <span>Kirim Pengajuan</span>
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let stream = null;
    const video = document.getElementById('webcam');
    const canvas = document.getElementById('faceCanvas');
    
    // GPS & Map Variables
    const absenType = '{{ $type }}';
    const kantorLat = {{ $pengaturan->latitude_kantor ?? 0 }};
    const kantorLng = {{ $pengaturan->longitude_kantor ?? 0 }};
    const maxRadius = {{ $pengaturan->radius_meter ?? 50 }};
    let map, userMarker, watchId;
    let locationValid = false;

    function initMap() {
        if(absenType !== 'masuk' || !document.getElementById('map')) return;
        
        map = L.map('map').setView([kantorLat, kantorLng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Circle Kantor
        L.circle([kantorLat, kantorLng], {
            color: 'green',
            fillColor: '#22c55e',
            fillOpacity: 0.2,
            radius: maxRadius
        }).addTo(map).bindPopup("Area Kantor");

        watchLocation();
    }

    function watchLocation() {
        if (navigator.geolocation) {
            watchId = navigator.geolocation.watchPosition(updatePosition, showError, {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            });
        } else {
            alert("Geolocation tidak didukung oleh browser ini.");
        }
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3; // metres
        const φ1 = lat1 * Math.PI/180;
        const φ2 = lat2 * Math.PI/180;
        const Δφ = (lat2-lat1) * Math.PI/180;
        const Δλ = (lon2-lon1) * Math.PI/180;

        const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ/2) * Math.sin(Δλ/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    function updatePosition(position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        const distance = Math.round(calculateDistance(lat, lng, kantorLat, kantorLng));
        
        document.getElementById('lat_masuk').value = lat;
        document.getElementById('long_masuk').value = lng;
        document.getElementById('jarak_masuk').value = distance;

        if (!userMarker) {
            userMarker = L.marker([lat, lng]).addTo(map);
        } else {
            userMarker.setLatLng([lat, lng]);
        }
        
        map.setView([lat, lng]);

        const statusDiv = document.getElementById('locationStatus');
        const btnSubmit = document.getElementById('btnSubmitAbsen');
        
        if (distance <= maxRadius) {
            locationValid = true;
            statusDiv.className = 'alert mb-4';
            statusDiv.style.backgroundColor = '#ECFDF5';
            statusDiv.style.color = '#065F46';
            statusDiv.style.border = '1px solid #A7F3D0';
            statusDiv.innerHTML = `<i class="ph ph-check-circle"></i> Anda berada dalam radius (${distance} meter). Bisa absen.`;
            
            // Check if camera is also ready
            if(stream) btnSubmit.disabled = false;
        } else {
            locationValid = false;
            statusDiv.className = 'alert mb-4';
            statusDiv.style.backgroundColor = '#FEF2F2';
            statusDiv.style.color = '#991B1B';
            statusDiv.style.border = '1px solid #FECACA';
            statusDiv.innerHTML = `<i class="ph ph-warning-circle"></i> Anda di luar radius (${distance}m / max ${maxRadius}m). Tidak bisa absen.`;
            btnSubmit.disabled = true;
        }
    }

    function showError(error) {
        const statusDiv = document.getElementById('locationStatus');
        if(!statusDiv) return;
        
        statusDiv.className = 'alert mb-4';
        statusDiv.style.backgroundColor = '#FEF2F2';
        statusDiv.style.color = '#991B1B';
        statusDiv.style.border = '1px solid #FECACA';
        
        switch(error.code) {
            case error.PERMISSION_DENIED:
                statusDiv.innerHTML = "Akses lokasi ditolak. Izinkan lokasi untuk absen.";
                break;
            case error.POSITION_UNAVAILABLE:
                statusDiv.innerHTML = "Informasi lokasi tidak tersedia.";
                break;
            case error.TIMEOUT:
                statusDiv.innerHTML = "Request lokasi timeout.";
                break;
            default:
                statusDiv.innerHTML = "Terjadi kesalahan yang tidak diketahui.";
                break;
        }
        document.getElementById('btnSubmitAbsen').disabled = true;
    }

    function toggleForm() {
        const val = document.querySelector('input[name="keterangan_type"]:checked').value;
        const formHadir = document.getElementById('form-hadir');
        const formIzinSakit = document.getElementById('form-izin-sakit');
        const inputJenis = document.getElementById('inputJenis');
        const btnIzinSakit = formIzinSakit.querySelector('button[type="submit"]');

        if(val === 'hadir') {
            formHadir.style.display = 'block';
            formIzinSakit.style.display = 'none';
            if(!stream) startCamera();
            if(absenType === 'masuk') setTimeout(() => { if(map) map.invalidateSize(); }, 200);
        } else {
            formHadir.style.display = 'none';
            formIzinSakit.style.display = 'block';
            inputJenis.value = val;
            if(val === 'sakit') {
                btnIzinSakit.className = 'btn w-100 btn-danger';
                btnIzinSakit.innerHTML = '<i class="ph ph-first-aid"></i><span>Kirim Pengajuan Sakit</span>';
            } else {
                btnIzinSakit.className = 'btn w-100 btn-warning';
                btnIzinSakit.innerHTML = '<i class="ph ph-envelope-simple"></i><span>Kirim Pengajuan Izin</span>';
            }
            stopCamera();
        }
    }

    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' }
            });
            video.srcObject = stream;
            await video.play();

            document.getElementById('camDot').classList.add('on');
            document.getElementById('camLabel').textContent = 'Kamera Aktif';
            
            // Re-check conditions to enable button
            if (absenType === 'masuk' && !locationValid) {
                document.getElementById('btnSubmitAbsen').disabled = true;
            } else {
                document.getElementById('btnSubmitAbsen').disabled = false;
            }

            video.addEventListener('loadedmetadata', () => {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
            });
        } catch (err) {
            document.getElementById('camLabel').textContent = 'Kamera tidak aktif';
            alert('Tidak dapat mengakses kamera. Pastikan browser Anda memiliki izin untuk menggunakan kamera.');
        }
    }

    function stopCamera() {
        if(stream) {
            stream.getTracks().forEach(t => t.stop());
            stream = null;
        }
    }

    function submitAbsen() {
        if (!stream) {
            alert('Kamera belum aktif.');
            return;
        }
        
        if (absenType === 'masuk' && !locationValid) {
            alert('Lokasi Anda di luar radius. Tidak dapat mengirim presensi.');
            return;
        }
        
        // Capture Foto
        const context = canvas.getContext('2d');
        // Mirror the image because video is scaled -1 in CSS
        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const dataUrl = canvas.toDataURL('image/jpeg');
        document.getElementById('inputFoto').value = dataUrl;

        const btn = document.getElementById('btnSubmitAbsen');
        btn.innerHTML = '<i class="ph ph-circle-notch spin"></i><span>Mengirim...</span>';
        btn.disabled = true;
        
        stopCamera();
        if(watchId) navigator.geolocation.clearWatch(watchId);
        
        document.getElementById('form-hadir').submit();
    }

    window.addEventListener('load', function() {
        if(document.querySelector('input[name="keterangan_type"]:checked').value === 'hadir') {
            startCamera();
            initMap();
        }
    });

    window.addEventListener('beforeunload', function() {
        stopCamera();
        if(watchId) navigator.geolocation.clearWatch(watchId);
    });
</script>
@endpush

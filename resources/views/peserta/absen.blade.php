@extends('layouts.app')

@section('title', 'Form Presensi PKL')

@push('styles')
<style>
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

    /* Webcam & Face Recog */
    .webcam-wrapper {
        position: relative;
        background: #0f172a;
        border-radius: 14px;
        overflow: hidden;
        aspect-ratio: 4/3;
        margin-bottom: 1rem;
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
    .face-guide {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 170px; height: 210px;
        border: 2.5px solid rgba(255,255,255,0.25);
        border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
        pointer-events: none;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .face-guide.detected {
        border-color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34,197,94,0.15);
    }
    .face-guide.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239,68,68,0.15);
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

    .status-card {
        background: white; border-radius: 10px; padding: 1rem;
        border: 1px solid #E2E8F0; display: flex; align-items: center;
        gap: 0.75rem; margin-bottom: 1rem; font-size: 0.875rem;
    }
    .status-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
    }
    .s-loading { background: #EFF6FF; color: #3B82F6; }
    .s-idle { background: #F1F5F9; color: #64748B; }
    .s-success { background: #F0FDF4; color: #22c55e; }
    .s-error { background: #FEF2F2; color: #ef4444; }
    
    .status-text strong { display: block; color: #1E293B; font-weight: 600; }
    .status-text small { color: #64748B; font-size: 0.78rem; }
    .spin { animation: spin 1s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
<div class="student-dashboard">
    <!-- Sidebar -->
    <div class="student-sidebar">
        <div class="student-brand">
            <div class="student-brand-mark">
                <i class="ph ph-map-pin-line"></i>
            </div>
            <div>
                <strong>SIAP PKL</strong>
                <span>Kec. Cikampek</span>
            </div>
        </div>

        <nav class="student-nav">
            <a href="{{ route('peserta.dashboard') }}" class="student-nav-item">
                <i class="ph ph-squares-four"></i>
                Dashboard
            </a>
            <a href="{{ route('peserta.riwayat') }}" class="student-nav-item">
                <i class="ph ph-clock-counter-clockwise"></i>
                Riwayat Presensi
            </a>
        </nav>

        <div class="student-sidebar-footer">
            <div class="student-mini-profile">
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" class="avatar" alt="Foto">
                @else
                    <div class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                @endif
                <div>
                    <strong>{{ Auth::user()->name }}</strong>
                    <span>Peserta PKL</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="student-logout" title="Keluar">
                    <i class="ph ph-sign-out"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="student-main">
        <div class="student-topbar">
            <div>
                <div class="student-eyebrow">Aktivitas</div>
                <h1>Kirim Presensi ({{ ucfirst($type) }})</h1>
            </div>
            <div class="student-date">
                <i class="ph ph-calendar-blank"></i>
                <span class="font-semibold">{{ date('l, d F Y') }}</span>
            </div>
        </div>

        <div class="student-content" style="max-width: 800px;">
            <div class="card p-5">
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

                <!-- FORM HADIR (Face Recognition) -->
                <form id="form-hadir" method="POST" action="{{ $type === 'masuk' ? route('absen.masuk') : route('absen.pulang') }}">
                    @csrf
                    <div class="form-group mt-4 pt-4" style="border-top: 1px dashed var(--border);">
                        <label class="form-label mb-3">Autentikasi Wajah (Wajib)</label>

                        <div class="webcam-wrapper">
                            <video id="webcam" autoplay playsinline muted></video>
                            <canvas id="faceCanvas"></canvas>
                            <div class="face-guide" id="faceGuide"></div>
                            <div class="webcam-overlay">
                                <div class="cam-dot" id="camDot"></div>
                                <span style="color:white; font-size: 0.8rem; font-weight: 600;" id="camLabel">Menyiapkan kamera...</span>
                            </div>
                        </div>

                        <div id="statusCard" class="status-card">
                            <div class="status-icon s-loading"><i class="ph ph-circle-notch spin"></i></div>
                            <div class="status-text">
                                <strong>Memuat sistem pengenalan wajah</strong>
                                <small>Mohon tunggu sebentar...</small>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="foto" id="inputFoto">

                    <button type="button" id="btnSubmitAbsen" class="btn btn-primary w-100" style="padding: 1rem; font-size: 1.1rem; border-radius: var(--radius-lg);" disabled onclick="submitAbsen()">
                        <i class="ph ph-paper-plane-tilt"></i>
                        <span>Kirim Presensi Hadir</span>
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
<script src="{{ asset('js/face-api.min.js') }}"></script>
<script>
    const MODEL_PATH = '/models';
    const MAX_DISTANCE = 0.45; // Euclidean distance threshold
    
    let stream = null;
    let detectionInterval = null;
    let modelsLoaded = false;
    let registeredDescriptor = null;

    const video = document.getElementById('webcam');
    const canvas = document.getElementById('faceCanvas');
    const faceGuide = document.getElementById('faceGuide');

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

    function setStatus(type, title, subtitle) {
        const icons = {
            loading: '<i class="ph ph-circle-notch spin"></i>',
            idle: '<i class="ph ph-scan"></i>',
            success: '<i class="ph ph-check-circle"></i>',
            error: '<i class="ph ph-x-circle"></i>'
        };
        const statusCard = document.getElementById('statusCard');
        if (statusCard) {
            statusCard.innerHTML = `
                <div class="status-icon s-${type}">${icons[type]}</div>
                <div class="status-text">
                    <strong>${title}</strong>
                    ${subtitle ? `<small>${subtitle}</small>` : ''}
                </div>`;
        }
    }

    function setButtonReady(ready) {
        const btn = document.getElementById('btnSubmitAbsen');
        if(btn) {
            btn.disabled = !ready;
            if(ready) {
                btn.style.background = 'linear-gradient(135deg, #059669, #10B981)';
                btn.style.boxShadow = '0 4px 12px rgba(16,185,129,0.3)';
            } else {
                btn.style.background = '';
                btn.style.boxShadow = '';
            }
        }
    }

    async function fetchRegisteredFace() {
        try {
            const res = await fetch('{{ route('absen.get_descriptor') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success && data.descriptor) {
                registeredDescriptor = new Float32Array(Object.values(data.descriptor));
                return true;
            } else {
                setStatus('error', 'Wajah Belum Terdaftar', data.message || 'Silakan hubungi Admin untuk registrasi.');
                return false;
            }
        } catch (err) {
            setStatus('error', 'Gagal memuat data wajah', 'Terjadi kesalahan koneksi server.');
            return false;
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

            video.addEventListener('loadedmetadata', () => {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
            });

            if(!modelsLoaded) {
                await loadModels();
            } else {
                startDetection();
            }
        } catch (err) {
            document.getElementById('camLabel').textContent = 'Kamera tidak aktif';
            if (err.name === 'NotAllowedError') {
                setStatus('error', 'Izin kamera ditolak', 'Berikan izin kamera pada browser Anda.');
            } else {
                setStatus('error', 'Kamera bermasalah', err.message);
            }
        }
    }

    function stopCamera() {
        if(detectionInterval) clearInterval(detectionInterval);
        if(stream) {
            stream.getTracks().forEach(t => t.stop());
            stream = null;
        }
    }

    async function loadModels() {
        setStatus('loading', 'Memuat sistem pengenalan wajah...', 'Mohon tunggu sebentar.');
        try {
            const hasRegisteredFace = await fetchRegisteredFace();
            if(!hasRegisteredFace) return;

            await Promise.all([
                faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_PATH),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_PATH),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_PATH),
            ]);
            modelsLoaded = true;
            setStatus('idle', 'Arahkan wajah ke kamera', 'Sistem akan mencocokkan wajah Anda.');
            startDetection();
        } catch (err) {
            setStatus('error', 'Gagal memuat model AI', 'Refresh halaman dan coba lagi.');
        }
    }

    function startDetection() {
        if (detectionInterval) clearInterval(detectionInterval);

        detectionInterval = setInterval(async () => {
            if (!modelsLoaded || !stream || !registeredDescriptor) return;

            try {
                const detections = await faceapi
                    .detectAllFaces(video, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.5 }))
                    .withFaceLandmarks()
                    .withFaceDescriptors();

                if (detections.length === 0) {
                    faceGuide.className = 'face-guide';
                    setStatus('idle', 'Wajah tidak terdeteksi', 'Arahkan wajah ke kamera.');
                    setButtonReady(false);
                    return;
                }

                if (detections.length > 1) {
                    faceGuide.className = 'face-guide error';
                    setStatus('error', 'Terlalu banyak wajah', 'Pastikan hanya ada Anda di depan kamera.');
                    setButtonReady(false);
                    return;
                }

                // Cocokkan wajah (1 wajah terdeteksi)
                const currentDescriptor = detections[0].descriptor;
                const distance = faceapi.euclideanDistance(currentDescriptor, registeredDescriptor);

                if (distance < MAX_DISTANCE) {
                    faceGuide.className = 'face-guide detected';
                    setStatus('success', 'Wajah Cocok', 'Autentikasi berhasil. Klik tombol Kirim Presensi.');
                    setButtonReady(true);
                } else {
                    faceGuide.className = 'face-guide error';
                    setStatus('error', 'Wajah Tidak Dikenali', 'Wajah tidak sesuai dengan data registrasi.');
                    setButtonReady(false);
                }

            } catch (err) {
                console.error(err);
            }
        }, 500);
    }

    function submitAbsen() {
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
        document.getElementById('form-hadir').submit();
    }

    window.addEventListener('load', function() {
        if(document.querySelector('input[name="keterangan_type"]:checked').value === 'hadir') {
            startCamera();
        }
    });

    window.addEventListener('beforeunload', stopCamera);
</script>
@endpush

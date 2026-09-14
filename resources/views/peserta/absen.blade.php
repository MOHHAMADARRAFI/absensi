@extends('layouts.app')

@section('title', 'Presensi ' . ucfirst($type) . ' - SIAP PKL')

@push('styles')
<style>
    .absen-container {
        max-width: 480px;
        margin: 0 auto;
        padding: 0;
        min-height: 100vh;
        background: var(--bg-light, #F8FAFC);
        display: flex;
        flex-direction: column;
    }

    /* Header */
    .absen-header {
        background: var(--primary, #3B82F6);
        background: linear-gradient(135deg, #1D4ED8, #3B82F6);
        color: white;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .absen-header a {
        color: white;
        font-size: 1.4rem;
        display: flex;
        align-items: center;
    }
    .absen-header h2 {
        font-size: 1.1rem;
        font-weight: 700;
    }

    /* Webcam Box */
    .webcam-wrapper {
        position: relative;
        background: #0f172a;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 4/3;
        width: 100%;
        margin-bottom: 1rem;
    }
    #webcam {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transform: scaleX(-1); /* Mirror effect */
    }
    #faceCanvas {
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
        transform: scaleX(-1);
    }
    .webcam-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.6));
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .webcam-indicator {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #ef4444;
        animation: blink 1s infinite;
    }
    .webcam-indicator.active { background: #22c55e; animation: none; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }
    .webcam-label {
        color: white;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Face Guide Box */
    .face-guide {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 160px;
        height: 200px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
        pointer-events: none;
        transition: border-color 0.3s;
    }
    .face-guide.face-detected { border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,0.2); }
    .face-guide.face-matched { border-color: #22c55e; }
    .face-guide.face-mismatch { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,0.2); }

    /* Status Cards */
    .status-stack {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .status-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: white;
        border-radius: 10px;
        border: 1px solid #E2E8F0;
        font-size: 0.875rem;
    }
    .status-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .status-icon.idle { background: #F1F5F9; color: #64748B; }
    .status-icon.loading { background: #EFF6FF; color: #3B82F6; }
    .status-icon.success { background: #F0FDF4; color: #22c55e; }
    .status-icon.error { background: #FEF2F2; color: #ef4444; }
    .status-icon.warning { background: #FFFBEB; color: #f59e0b; }

    .status-text strong { display: block; font-weight: 600; color: #1E293B; }
    .status-text small { color: #64748B; font-size: 0.78rem; }

    /* Spinner */
    .spin { animation: spin 1s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Submit Button */
    #btnSubmitAbsen {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
        font-weight: 700;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s;
        background: #E2E8F0;
        color: #94A3B8;
    }
    #btnSubmitAbsen.ready {
        background: linear-gradient(135deg, #1D4ED8, #3B82F6);
        color: white;
        box-shadow: 0 4px 12px rgba(59,130,246,0.3);
    }
    #btnSubmitAbsen.ready:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(59,130,246,0.4);
    }
    #btnSubmitAbsen:disabled {
        cursor: not-allowed;
        opacity: 0.8;
    }

    /* Keterangan Selector */
    .keterangan-selector {
        display: flex;
        gap: 0.35rem;
        background: #F1F5F9;
        padding: 0.3rem;
        border-radius: 10px;
        margin-bottom: 1.25rem;
    }
    .keterangan-selector input[type="radio"] { display: none; }
    .keterangan-selector label {
        flex: 1;
        text-align: center;
        padding: 0.65rem 0;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.875rem;
        color: #64748B;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .keterangan-selector input:checked + label {
        background: white;
        color: #1D4ED8;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }

    /* Izin/Sakit Form */
    #form-izin-sakit { display: none; }

    /* Loading model overlay */
    .model-loading {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        text-align: center;
        color: #64748B;
        font-size: 0.875rem;
        gap: 0.75rem;
    }
    .model-loading i { font-size: 2rem; color: #3B82F6; }
</style>
@endpush

@section('content')
<div class="absen-container">

    {{-- Header --}}
    <div class="absen-header">
        <a href="{{ route('peserta.dashboard') }}"><i class="ph ph-arrow-left"></i></a>
        <div>
            <h2>Presensi {{ ucfirst($type) }}</h2>
            <p style="font-size: 0.8rem; opacity: 0.85;">
                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </p>
        </div>
    </div>

    <div style="padding: 1.25rem; flex: 1;">

        {{-- Info Peserta --}}
        <div style="background: white; border-radius: 10px; padding: 0.875rem 1rem; margin-bottom: 1.25rem; border: 1px solid #E2E8F0;">
            <div style="font-size: 0.8rem; color: #64748B;">Peserta</div>
            <div style="font-weight: 700; color: #1E293B;">{{ $user->name }}</div>
            <div style="font-size: 0.78rem; color: #94A3B8;">{{ $user->nis_nim }} — {{ $user->divisi }}</div>
        </div>

        {{-- Keterangan Selector (hanya untuk absen masuk) --}}
        @if($type === 'masuk')
        <div>
            <label style="font-size: 0.8rem; font-weight: 600; color: #64748B; display: block; margin-bottom: 0.4rem;">Keterangan</label>
            <div class="keterangan-selector">
                <input type="radio" id="ket_hadir" name="keterangan_type" value="hadir" checked onchange="toggleForm()">
                <label for="ket_hadir">Hadir</label>

                <input type="radio" id="ket_izin" name="keterangan_type" value="izin" onchange="toggleForm()">
                <label for="ket_izin">Izin</label>

                <input type="radio" id="ket_sakit" name="keterangan_type" value="sakit" onchange="toggleForm()">
                <label for="ket_sakit">Sakit</label>
            </div>
        </div>
        @endif

        {{-- === FORM HADIR (Face Recognition) === --}}
        <div id="form-hadir">

            {{-- Cek wajah sudah terdaftar --}}
            @if(!$user->face_descriptor)
            <div class="card text-center" style="padding: 2rem 1.5rem;">
                <i class="ph ph-user-circle-minus" style="font-size: 3rem; color: #f59e0b; display: block; margin-bottom: 1rem;"></i>
                <h4 class="font-bold mb-2">Wajah Belum Terdaftar</h4>
                <p class="text-secondary" style="font-size: 0.875rem; line-height: 1.6;">
                    Data wajah Anda belum terdaftar di sistem.<br>
                    Silakan hubungi Admin untuk melakukan <strong>registrasi wajah</strong> terlebih dahulu.
                </p>
                <a href="{{ route('peserta.dashboard') }}" class="btn btn-outline mt-4" style="display: inline-flex; width: auto; margin: 1rem auto 0;">
                    <i class="ph ph-arrow-left"></i> Kembali
                </a>
            </div>
            @else

            {{-- Webcam --}}
            <div class="webcam-wrapper" id="webcamWrapper">
                <video id="webcam" autoplay playsinline muted></video>
                <canvas id="faceCanvas"></canvas>
                <div class="face-guide" id="faceGuide"></div>
                <div class="webcam-overlay">
                    <div class="webcam-indicator" id="camIndicator"></div>
                    <span class="webcam-label" id="camLabel">Menyiapkan kamera...</span>
                </div>
            </div>

            {{-- Status Stack --}}
            <div class="status-stack" id="statusStack">
                <div class="status-item" id="statusModel">
                    <div class="status-icon loading"><i class="ph ph-circle-notch spin"></i></div>
                    <div class="status-text">
                        <strong>Memuat sistem pengenalan wajah</strong>
                        <small>Mohon tunggu sebentar...</small>
                    </div>
                </div>
            </div>

            {{-- Form submit --}}
            <form id="form-hadir-submit" method="POST"
                action="{{ $type === 'masuk' ? route('absen.masuk') : route('absen.pulang') }}">
                @csrf
                <input type="hidden" name="foto" id="inputFoto">
                <input type="hidden" name="face_verified" id="inputFaceVerified" value="0">

                <button type="button" id="btnSubmitAbsen" disabled onclick="doSubmitAbsen()">
                    <i class="ph ph-lock"></i>
                    <span id="btnText">Menunggu Verifikasi Wajah</span>
                </button>
            </form>

            @endif
        </div>

        {{-- === FORM IZIN/SAKIT === --}}
        @if($type === 'masuk')
        <form id="form-izin-sakit" method="POST"
            action="{{ route('peserta.submit_izin_sakit') }}"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis" id="inputJenis" value="izin">
            <input type="hidden" name="tanggal_mulai" value="{{ date('Y-m-d') }}">
            <input type="hidden" name="tanggal_selesai" value="{{ date('Y-m-d') }}">

            <div class="form-group">
                <label class="form-label">Alasan</label>
                <input type="text" name="alasan" class="form-control" required
                    placeholder="Cth: Keperluan keluarga / Sakit demam">
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan Detail (Opsional)</label>
                <textarea name="keterangan" class="form-control" rows="3"
                    placeholder="Jelaskan secara rinci..."></textarea>
            </div>

            <div class="form-group mb-4">
                <label class="form-label">Bukti Dokumen (Opsional)</label>
                <input type="file" name="bukti_dokumen" class="form-control" accept="image/*,.pdf">
                <small class="text-secondary" style="font-size: 0.75rem;">Format: JPG, PNG, PDF. Max 2MB.</small>
            </div>

            <button type="submit" id="btnIzinSakit"
                class="btn btn-warning w-100"
                style="padding: 1rem; font-size: 1rem; border-radius: 12px; color: white;">
                <i class="ph ph-paper-plane-tilt"></i>
                <span id="btnIzinLabel">Kirim Pengajuan Izin</span>
            </button>
        </form>
        @endif

    </div>
</div>
@endsection

@push('scripts')
{{-- face-api.js dimuat dari lokal, tidak butuh internet --}}
<script src="{{ asset('js/face-api.min.js') }}"></script>
<script>
// ============================================================
// KONFIGURASI FACE RECOGNITION
// ============================================================
const FACE_MATCH_THRESHOLD = 0.50; // Semakin kecil = semakin ketat
const DETECTION_INTERVAL_MS = 500;  // Interval deteksi (ms)
const MODEL_PATH = '/models';        // Path model lokal

// ============================================================
// STATE
// ============================================================
let stream = null;
let detectionInterval = null;
let faceVerified = false;
let modelsLoaded = false;
let capturedFotoDataUrl = null;
let storedDescriptor = null; // Descriptor dari server (milik user yang login)

const video = document.getElementById('webcam');
const canvas = document.getElementById('faceCanvas');
const faceGuide = document.getElementById('faceGuide');
const camIndicator = document.getElementById('camIndicator');
const camLabel = document.getElementById('camLabel');

// ============================================================
// UTILITAS STATUS
// ============================================================
function setStatus(state, title, subtitle) {
    const icons = {
        idle: '<i class="ph ph-circle"></i>',
        loading: '<i class="ph ph-circle-notch spin"></i>',
        success: '<i class="ph ph-check-circle"></i>',
        error: '<i class="ph ph-x-circle"></i>',
        warning: '<i class="ph ph-warning"></i>',
    };
    const stack = document.getElementById('statusStack');
    stack.innerHTML = `
        <div class="status-item" id="statusModel">
            <div class="status-icon ${state}">${icons[state]}</div>
            <div class="status-text">
                <strong>${title}</strong>
                ${subtitle ? `<small>${subtitle}</small>` : ''}
            </div>
        </div>`;
}

function setButtonReady(ready) {
    const btn = document.getElementById('btnSubmitAbsen');
    const btnText = document.getElementById('btnText');
    if (ready) {
        btn.disabled = false;
        btn.classList.add('ready');
        btn.querySelector('i').className = 'ph ph-paper-plane-tilt';
        btnText.textContent = 'Kirim Presensi';
    } else {
        btn.disabled = true;
        btn.classList.remove('ready');
        btn.querySelector('i').className = 'ph ph-lock';
        btnText.textContent = 'Menunggu Verifikasi Wajah';
    }
}

// ============================================================
// LOAD MODELS
// ============================================================
async function loadModels() {
    setStatus('loading', 'Memuat sistem pengenalan wajah...', 'Proses ini hanya sekali saat halaman dibuka.');
    try {
        await Promise.all([
            faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_PATH),
            faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_PATH),
            faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_PATH),
        ]);
        modelsLoaded = true;
        setStatus('success', 'Sistem siap', 'Arahkan wajah Anda ke kamera.');
        // Ambil descriptor dari server
        await fetchDescriptor();
    } catch (err) {
        console.error('Model load error:', err);
        setStatus('error', 'Gagal memuat sistem pengenalan wajah', 'Pastikan file model tersedia di folder /models. Coba refresh halaman.');
    }
}

// ============================================================
// AMBIL DESCRIPTOR DARI SERVER
// ============================================================
async function fetchDescriptor() {
    try {
        setStatus('loading', 'Mengambil data wajah...', '');
        const res = await fetch('{{ route("absen.get_descriptor") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
        });
        const data = await res.json();

        if (!data.success) {
            if (data.not_registered) {
                setStatus('warning', 'Wajah belum terdaftar', 'Hubungi Admin untuk registrasi wajah Anda.');
            } else {
                setStatus('error', 'Gagal mengambil data wajah', data.message || '');
            }
            return;
        }

        storedDescriptor = new Float32Array(data.descriptor);
        setStatus('idle', 'Arahkan wajah ke kamera', 'Pastikan pencahayaan cukup dan hanya ada satu wajah.');
        startDetection();
    } catch (err) {
        console.error('Fetch descriptor error:', err);
        setStatus('error', 'Gagal terhubung ke server', 'Periksa koneksi dan coba lagi.');
    }
}

// ============================================================
// START KAMERA
// ============================================================
async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 640 },
                height: { ideal: 480 },
                facingMode: 'user'
            }
        });
        video.srcObject = stream;
        await video.play();

        camIndicator.classList.add('active');
        camLabel.textContent = 'Kamera Aktif';

        video.addEventListener('loadedmetadata', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
        });

        await loadModels();
    } catch (err) {
        console.error('Camera error:', err);
        camLabel.textContent = 'Kamera tidak dapat diakses';
        if (err.name === 'NotAllowedError') {
            setStatus('error', 'Izin kamera ditolak', 'Berikan izin akses kamera pada browser, lalu refresh halaman.');
        } else if (err.name === 'NotFoundError') {
            setStatus('error', 'Kamera tidak ditemukan', 'Pastikan webcam terpasang dan terhubung dengan benar.');
        } else {
            setStatus('error', 'Kamera tidak dapat digunakan', 'Pesan: ' + err.message);
        }
    }
}

// ============================================================
// DETEKSI & PENCOCOKAN WAJAH
// ============================================================
function startDetection() {
    if (detectionInterval) clearInterval(detectionInterval);

    detectionInterval = setInterval(async () => {
        if (!modelsLoaded || !stream || faceVerified) return;

        try {
            const detections = await faceapi
                .detectAllFaces(video, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.5 }))
                .withFaceLandmarks()
                .withFaceDescriptors();

            // Hapus canvas sebelumnya
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // ============ Tidak ada wajah ============
            if (detections.length === 0) {
                faceGuide.className = 'face-guide';
                setStatus('idle', 'Wajah tidak terdeteksi', 'Arahkan wajah Anda ke tengah kamera.');
                return;
            }

            // ============ Lebih dari 1 wajah ============
            if (detections.length > 1) {
                faceGuide.className = 'face-guide face-mismatch';
                setStatus('error', 'Lebih dari satu wajah terdeteksi', 'Pastikan hanya Anda yang berada di depan kamera.');
                return;
            }

            // ============ Tepat 1 wajah ============
            const detection = detections[0];
            faceGuide.className = 'face-guide face-detected';

            // Jika descriptor belum ada, tidak bisa bandingkan
            if (!storedDescriptor) {
                setStatus('warning', 'Data wajah belum tersedia', 'Hubungi Admin untuk registrasi wajah.');
                return;
            }

            // Bandingkan descriptor
            const distance = faceapi.euclideanDistance(
                detection.descriptor,
                storedDescriptor
            );

            const isMatch = distance <= FACE_MATCH_THRESHOLD;

            if (isMatch) {
                // ============ COCOK ============
                faceGuide.className = 'face-guide face-matched';
                setStatus('success', '✓ Wajah berhasil diverifikasi', `Identitas cocok. Klik tombol untuk melanjutkan.`);

                faceVerified = true;
                clearInterval(detectionInterval);
                detectionInterval = null;

                // Capture foto
                const captureCanvas = document.createElement('canvas');
                captureCanvas.width = video.videoWidth;
                captureCanvas.height = video.videoHeight;
                captureCanvas.getContext('2d').drawImage(video, 0, 0);
                capturedFotoDataUrl = captureCanvas.toDataURL('image/jpeg', 0.85);

                document.getElementById('inputFoto').value = capturedFotoDataUrl;
                document.getElementById('inputFaceVerified').value = '1';

                setButtonReady(true);

            } else {
                // ============ TIDAK COCOK ============
                faceGuide.className = 'face-guide face-mismatch';
                setStatus('error', 'Wajah tidak cocok', `Wajah tidak sesuai dengan akun Anda. Jarak: ${distance.toFixed(3)}`);
            }

        } catch (err) {
            console.error('Detection error:', err);
        }
    }, DETECTION_INTERVAL_MS);
}

// ============================================================
// SUBMIT ABSEN
// ============================================================
function doSubmitAbsen() {
    if (!faceVerified) {
        setStatus('error', 'Verifikasi wajah diperlukan', 'Arahkan wajah ke kamera terlebih dahulu.');
        return;
    }
    if (!capturedFotoDataUrl) {
        setStatus('error', 'Foto tidak tersedia', 'Pastikan kamera aktif dan coba lagi.');
        return;
    }

    const btn = document.getElementById('btnSubmitAbsen');
    const btnText = document.getElementById('btnText');
    btn.disabled = true;
    btnText.textContent = 'Menyimpan presensi...';
    btn.querySelector('i').className = 'ph ph-circle-notch spin';

    document.getElementById('form-hadir-submit').submit();
}

// ============================================================
// TOGGLE FORM HADIR / IZIN-SAKIT
// ============================================================
function toggleForm() {
    const val = document.querySelector('input[name="keterangan_type"]:checked').value;
    const formHadir = document.getElementById('form-hadir');
    const formIzinSakit = document.getElementById('form-izin-sakit');
    const btnIzinSakit = document.getElementById('btnIzinSakit');
    const btnIzinLabel = document.getElementById('btnIzinLabel');

    if (val === 'hadir') {
        formHadir.style.display = 'block';
        formIzinSakit.style.display = 'none';
    } else {
        formHadir.style.display = 'none';
        formIzinSakit.style.display = 'block';
        document.getElementById('inputJenis').value = val;

        if (val === 'sakit') {
            btnIzinSakit.className = 'btn btn-danger w-100';
            btnIzinLabel.textContent = 'Kirim Pengajuan Sakit';
        } else {
            btnIzinSakit.className = 'btn btn-warning w-100';
            btnIzinLabel.textContent = 'Kirim Pengajuan Izin';
        }

        // Hentikan kamera saat tidak digunakan
        if (stream && val !== 'hadir') {
            stopCamera();
        }
    }
}

function stopCamera() {
    if (detectionInterval) {
        clearInterval(detectionInterval);
        detectionInterval = null;
    }
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    camIndicator.classList.remove('active');
    camLabel.textContent = 'Kamera tidak aktif';
}

// ============================================================
// INISIALISASI
// ============================================================
window.addEventListener('load', function () {
    @if($user->face_descriptor)
    const initialKet = document.querySelector('input[name="keterangan_type"]:checked');
    if (!initialKet || initialKet.value === 'hadir') {
        startCamera();
    }
    @endif
});

window.addEventListener('beforeunload', function () {
    stopCamera();
});
</script>
@endpush

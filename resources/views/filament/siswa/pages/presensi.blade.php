<x-filament-panels::page>

    <style>
        /* OVERRIDE FILAMENT DARK MODE */
        .fi-simple-presensi .presensi-card {
            background: white !important;
            border-radius: 0.75rem !important;
            border: 1px solid #e5e7eb !important;
            padding: 1.5rem !important;
            margin-bottom: 1.5rem !important;
        }
        
        .dark .fi-simple-presensi .presensi-card {
            background: #1f2937 !important;
            border-color: #4b5563 !important;
        }
        
        .fi-simple-presensi .card-secondary {
            background: #f9fafb !important;
            border-radius: 0.5rem !important;
            border: 1px solid #e5e7eb !important;
            padding: 1.25rem !important;
        }
        
        .dark .fi-simple-presensi .card-secondary {
            background: #374151 !important;
            border-color: #4b5563 !important;
        }
        
        .fi-simple-presensi .action-card {
            background: #f3f4f6 !important;
            border-radius: 0.5rem !important;
            border: 1px solid #d1d5db !important;
            padding: 1.25rem !important;
        }
        
        .dark .fi-simple-presensi .action-card {
            background: #4b5563 !important;
            border-color: #6b7280 !important;
        }
        
        .fi-simple-presensi .text-primary {
            color: #111827 !important;
        }
        
        .dark .fi-simple-presensi .text-primary {
            color: #f9fafb !important;
        }
        
        .fi-simple-presensi .text-secondary {
            color: #6b7280 !important;
        }
        
        .dark .fi-simple-presensi .text-secondary {
            color: #d1d5db !important;
        }
        
        .fi-simple-presensi .border-color {
            border-color: #e5e7eb !important;
        }
        
        .dark .fi-simple-presensi .border-color {
            border-color: #4b5563 !important;
        }
        
        /* Success Colors */
        .fi-simple-presensi .success-bg { 
            background: #d1fae5 !important; 
        }
        
        .dark .fi-simple-presensi .success-bg { 
            background: #064e3b !important; 
        }
        
        .fi-simple-presensi .success-text { 
            color: #065f46 !important; 
        }
        
        .dark .fi-simple-presensi .success-text { 
            color: #a7f3d0 !important; 
        }
        
        /* Warning Colors */
        .fi-simple-presensi .warning-bg { 
            background: #fef3c7 !important; 
        }
        
        .dark .fi-simple-presensi .warning-bg { 
            background: #78350f !important; 
        }
        
        .fi-simple-presensi .warning-text { 
            color: #92400e !important; 
        }
        
        .dark .fi-simple-presensi .warning-text { 
            color: #fde68a !important; 
        }
        
        /* Danger Colors */
        .fi-simple-presensi .danger-bg { 
            background: #fee2e2 !important; 
        }
        
        .dark .fi-simple-presensi .danger-bg { 
            background: #7f1d1d !important; 
        }
        
        .fi-simple-presensi .danger-text { 
            color: #991b1b !important; 
        }
        
        .dark .fi-simple-presensi .danger-text { 
            color: #fca5a5 !important; 
        }
        
        /* Gray Colors */
        .fi-simple-presensi .gray-bg { 
            background: #f3f4f6 !important; 
        }
        
        .dark .fi-simple-presensi .gray-bg { 
            background: #374151 !important; 
        }
        
        .fi-simple-presensi .gray-text { 
            color: #6b7280 !important; 
        }
        
        .dark .fi-simple-presensi .gray-text { 
            color: #d1d5db !important; 
        }
        
        /* Buttons - Light Mode */
        .fi-simple-presensi .btn-success {
            background: #10b981 !important;
            color: white !important;
            border: none !important;
        }
        
        .fi-simple-presensi .btn-success:hover {
            background: #059669 !important;
        }
        
        .dark .fi-simple-presensi .btn-success {
            background: #059669 !important;
        }
        
        .dark .fi-simple-presensi .btn-success:hover {
            background: #047857 !important;
        }
        
        .fi-simple-presensi .btn-warning {
            background: #f59e0b !important;
            color: white !important;
            border: none !important;
        }
        
        .fi-simple-presensi .btn-warning:hover {
            background: #d97706 !important;
        }
        
        .dark .fi-simple-presensi .btn-warning {
            background: #d97706 !important;
        }
        
        .dark .fi-simple-presensi .btn-warning:hover {
            background: #b45309 !important;
        }
        
        .fi-simple-presensi .btn-info {
            background: #0ea5e9 !important;
            color: white !important;
            border: none !important;
        }
        
        .fi-simple-presensi .btn-info:hover {
            background: #0284c7 !important;
        }
        
        .dark .fi-simple-presensi .btn-info {
            background: #0284c7 !important;
        }
        
        .dark .fi-simple-presensi .btn-info:hover {
            background: #0369a1 !important;
        }
        
        .fi-simple-presensi .btn-gray {
            background: #6b7280 !important;
            color: white !important;
            border: none !important;
        }
        
        .fi-simple-presensi .btn-gray:hover {
            background: #4b5563 !important;
        }
        
        .dark .fi-simple-presensi .btn-gray {
            background: #6b7280 !important;
        }
        
        .dark .fi-simple-presensi .btn-gray:hover {
            background: #4b5563 !important;
        }
        
        .fi-simple-presensi .btn-light-gray {
            background: #f3f4f6 !important;
            color: #6b7280 !important;
            border: 1px solid #d1d5db !important;
        }
        
        .fi-simple-presensi .btn-light-gray:hover {
            background: #e5e7eb !important;
        }
        
        .dark .fi-simple-presensi .btn-light-gray {
            background: #4b5563 !important;
            color: #d1d5db !important;
            border-color: #6b7280 !important;
        }
        
        .dark .fi-simple-presensi .btn-light-gray:hover {
            background: #374151 !important;
        }
        
        /* Input Background */
        .fi-simple-presensi .input-bg {
            background: white !important;
        }
        
        .dark .fi-simple-presensi .input-bg {
            background: #374151 !important;
        }
        
        /* Disabled buttons */
        .fi-simple-presensi button:disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
        }
        
        /* No Schedule Message */
        .fi-simple-presensi .no-schedule-card {
            background: white !important;
            border-radius: 0.75rem !important;
            border: 1px solid #e5e7eb !important;
            padding: 3rem 1.5rem !important;
            text-align: center !important;
            margin-bottom: 1.5rem !important;
        }
        
        .dark .fi-simple-presensi .no-schedule-card {
            background: #1f2937 !important;
            border-color: #4b5563 !important;
        }
        
        .fi-simple-presensi .no-schedule-icon {
            font-size: 3rem !important;
            margin-bottom: 1rem !important;
            display: block !important;
        }
        
        .fi-simple-presensi .no-schedule-title {
            font-size: 1.5rem !important;
            font-weight: 600 !important;
            margin-bottom: 0.5rem !important;
            color: #111827 !important;
        }
        
        .dark .fi-simple-presensi .no-schedule-title {
            color: #f9fafb !important;
        }
        
        .fi-simple-presensi .no-schedule-message {
            color: #6b7280 !important;
            font-size: 1rem !important;
            max-width: 500px !important;
            margin: 0 auto !important;
        }
        
        .dark .fi-simple-presensi .no-schedule-message {
            color: #d1d5db !important;
        }
    </style>

    <div class="fi-simple-presensi" style="display: flex; flex-direction: column; gap: 2rem;">

        {{-- ================= TIDAK ADA JADWAL HARI INI ================= --}}
        @if(count($this->schedules) == 0)
            <div class="no-schedule-card">
                <span class="no-schedule-icon">📅</span>
                <h2 class="no-schedule-title">Tidak Ada Jadwal Presensi</h2>
                <p class="no-schedule-message">
                    Saat ini tidak ada jadwal ekstrakurikuler untuk hari ini.<br>
                    Silakan kembali pada hari dan jam yang telah ditentukan untuk melakukan presensi.
                </p>
            </div>
        @endif

        @foreach ($this->schedules as $schedule)
            @php
                $attendance = $this->getTodayAttendance($schedule);
                $canMasuk = $this->canAbsenMasuk($schedule);
                $canSetengah = $this->canAbsenSetengah($schedule);
                $canPulang = $this->canAbsenPulang($schedule);
                $canIzin = $this->canIzin($schedule);
            @endphp

            {{-- ================= MAIN CARD ================= --}}
            <div class="presensi-card">
                {{-- SIMPLE HEADER --}}
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 0.25rem;" class="text-primary">
                                🏫 {{ $schedule->ekstra->name }}
                            </h2>
                            <p class="text-secondary" style="font-size: 0.875rem;">
                                📍 {{ $schedule->roomEkstra->name }}
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <p class="text-secondary" style="font-size: 0.875rem; margin-bottom: 0.25rem;">
                                ⏰ Waktu Kegiatan
                            </p>
                            <p style="font-weight: 600;" class="text-primary">
                                {{ $schedule->start_time }} – {{ $schedule->end_time }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- STATUS INFO --}}
                <div style="
                    border-top: 1px solid;
                    border-bottom: 1px solid;
                    padding: 1rem 0;
                    margin-bottom: 1.5rem;
                " class="border-color">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            @if($attendance)
                                <span style="
                                    display: inline-block;
                                    padding: 0.5rem 1rem;
                                    border-radius: 9999px;
                                    font-weight: 600;
                                    font-size: 0.875rem;
                                " class="{{ $attendance->status === 'hadir' ? 'success-bg success-text' : 
                                           ($attendance->status === 'setengah' ? 'warning-bg warning-text' : 
                                           'danger-bg danger-text') }}">
                                    {{ strtoupper($attendance->status) }}
                                </span>
                            @else
                                <span style="
                                    display: inline-block;
                                    padding: 0.5rem 1rem;
                                    border-radius: 9999px;
                                    font-weight: 600;
                                    font-size: 0.875rem;
                                " class="gray-bg gray-text">
                                    BELUM ABSEN
                                </span>
                            @endif
                            
                            <div>
                                <div class="text-secondary" style="font-size: 0.875rem; margin-bottom: 0.25rem;">
                                    Status Presensi
                                </div>
                                <div style="display: flex; gap: 1.5rem;">
                                    <div>
                                        <div class="text-secondary" style="font-size: 0.75rem;">🚪 Masuk</div>
                                        <div style="font-weight: 600;" class="text-primary">
                                            {{ $attendance?->clock_in
                                                ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i')
                                                : '--:--' }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-secondary" style="font-size: 0.75rem;">🏁 Pulang</div>
                                        <div style="font-weight: 600;" class="text-primary">
                                            {{ $attendance?->clock_out
                                                ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i')
                                                : '--:--' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- GRID FITUR --}}
                <div style="
                    display: grid;
                    grid-template-columns: 1fr;
                    gap: 1.5rem;
                    margin-bottom: 1.5rem;
                ">
                    {{-- GPS TRACKER --}}
                    <div class="card-secondary">
                        <div x-data="gpsTracker()" x-init="startTracking()">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                                <h3 style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;" class="text-primary">
                                    <span>📍</span> Deteksi GPS
                                </h3>
                                <span x-show="lat && lng" style="
                                    display: inline-block;
                                    padding: 0.25rem 0.75rem;
                                    border-radius: 9999px;
                                    font-size: 0.75rem;
                                    font-weight: 500;
                                " class="success-bg success-text">
                                    AKTIF
                                </span>
                                <span x-show="!lat || !lng" style="
                                    display: inline-block;
                                    padding: 0.25rem 0.75rem;
                                    border-radius: 9999px;
                                    font-size: 0.75rem;
                                    font-weight: 500;
                                " class="danger-bg danger-text">
                                    OFFLINE
                                </span>
                            </div>
                            
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span class="text-secondary" style="font-size: 0.875rem;">Status Koneksi</span>
                                    <span x-show="lat && lng" class="success-text" style="font-weight: 500;">
                                        ✅ Terhubung
                                    </span>
                                    <span x-show="!lat || !lng" class="danger-text" style="font-weight: 500;">
                                        ❌ Terputus
                                    </span>
                                </div>
                                
                                {{-- <div x-show="lat && lng">
                                    <p class="text-secondary" style="font-size: 0.875rem; margin-bottom: 0.5rem;">Koordinat:</p>
                                    <div style="
                                        padding: 0.75rem;
                                        border-radius: 0.375rem;
                                        border: 1px solid;
                                    " class="input-bg border-color">
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                                            <span class="text-secondary" style="font-size: 0.875rem;">Latitude</span>
                                            <span style="font-family: monospace; font-size: 0.875rem;" class="text-primary" x-text="lat?.toFixed(6)"></span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between;">
                                            <span class="text-secondary" style="font-size: 0.875rem;">Longitude</span>
                                            <span style="font-family: monospace; font-size: 0.875rem;" class="text-primary" x-text="lng?.toFixed(6)"></span>
                                        </div>
                                    </div>
                                </div> --}}

                                <button
                                    x-show="!lat || !lng"
                                    @click="startTracking"
                                    style="
                                        width: 100%;
                                        margin-top: 0.75rem;
                                        padding: 0.5rem;
                                        border-radius: 0.375rem;
                                        font-size: 0.875rem;
                                        cursor: pointer;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        gap: 0.5rem;
                                        transition: background-color 0.2s ease;
                                    "
                                    class="btn-light-gray"
                                >
                                    🔄 Aktifkan GPS
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- FOTO MASUK --}}
                    <div class="card-secondary">
                        <div style="margin-bottom: 1rem;">
                            <h3 style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;" class="text-primary">
                                <span>📸</span> Foto Masuk
                            </h3>
                            <p class="text-secondary" style="font-size: 0.75rem;">Ambil foto saat absen masuk</p>
                        </div>
                        
                        <div x-data="cameraMasuk()" x-init="init()">
                            <div style="margin-bottom: 1rem; position: relative; aspect-ratio: 16/9; background: #000; border-radius: 0.5rem; overflow: hidden;">
                                <video 
                                    x-show="!photo" 
                                    x-ref="video" 
                                    autoplay 
                                    playsinline 
                                    style="width: 100%; height: 100%; object-fit: cover;"
                                ></video>
                                <img 
                                    x-show="photo" 
                                    :src="photo" 
                                    style="width: 100%; height: 100%; object-fit: cover;"
                                />
                                <div x-show="!photo" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.5);">
                                    <span style="color: white; font-size: 2rem;">📷</span>
                                </div>
                            </div>

                            <div style="display: flex; gap: 0.5rem;">
                                <button
                                    x-show="!photo"
                                    @click="capture"
                                    style="
                                        flex: 1;
                                        padding: 0.625rem;
                                        border-radius: 0.375rem;
                                        font-size: 0.875rem;
                                        cursor: pointer;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        gap: 0.5rem;
                                        transition: background-color 0.2s ease;
                                    "
                                    class="btn-success"
                                >
                                    📷 Ambil Foto
                                </button>

                                <button
                                    x-show="photo"
                                    @click="retake"
                                    style="
                                        flex: 1;
                                        padding: 0.625rem;
                                        border-radius: 0.375rem;
                                        font-size: 0.875rem;
                                        cursor: pointer;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        gap: 0.5rem;
                                        transition: background-color 0.2s ease;
                                    "
                                    class="btn-gray"
                                >
                                    🔄 Ulangi
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- FOTO PULANG --}}
                    <div class="card-secondary">
                        <div style="margin-bottom: 1rem;">
                            <h3 style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;" class="text-primary">
                                <span>📸</span> Foto Pulang
                            </h3>
                            <p class="text-secondary" style="font-size: 0.75rem;">Ambil foto saat absen pulang</p>
                        </div>
                        
                        <div x-data="cameraPulang()" x-init="init()">
                            <div style="margin-bottom: 1rem; position: relative; aspect-ratio: 16/9; background: #000; border-radius: 0.5rem; overflow: hidden;">
                                <video 
                                    x-show="!photo" 
                                    x-ref="video" 
                                    autoplay 
                                    playsinline 
                                    style="width: 100%; height: 100%; object-fit: cover;"
                                ></video>
                                <img 
                                    x-show="photo" 
                                    :src="photo" 
                                    style="width: 100%; height: 100%; object-fit: cover;"
                                />
                                <div x-show="!photo" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.5);">
                                    <span style="color: white; font-size: 2rem;">📷</span>
                                </div>
                            </div>

                            <div style="display: flex; gap: 0.5rem;">
                                <button
                                    x-show="!photo"
                                    @click="capture"
                                    style="
                                        flex: 1;
                                        padding: 0.625rem;
                                        border-radius: 0.375rem;
                                        font-size: 0.875rem;
                                        cursor: pointer;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        gap: 0.5rem;
                                        transition: background-color 0.2s ease;
                                    "
                                    class="btn-warning"
                                >
                                    📷 Ambil Foto
                                </button>

                                <button
                                    x-show="photo"
                                    @click="retake"
                                    style="
                                        flex: 1;
                                        padding: 0.625rem;
                                        border-radius: 0.375rem;
                                        font-size: 0.875rem;
                                        cursor: pointer;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        gap: 0.5rem;
                                        transition: background-color 0.2s ease;
                                    "
                                    class="btn-gray"
                                >
                                    🔄 Ulangi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="action-card">
                    <h3 style="font-weight: 600; text-align: center; margin-bottom: 1.5rem;" class="text-primary">
                        ✅ Aksi Presensi
                    </h3>
                    
                    <div style="
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: 1rem;
                    ">
                        @if($canMasuk)
                            <button
                                wire:click="absenMasuk({{ $schedule->id }})"
                                style="
                                    padding: 1rem;
                                    border-radius: 0.5rem;
                                    font-size: 0.875rem;
                                    font-weight: 500;
                                    cursor: pointer;
                                    height: 3.5rem;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    gap: 0.5rem;
                                    transition: background-color 0.2s ease;
                                "
                                class="btn-success"
                            >
                                👤 Absen Masuk
                            </button>
                        @endif

                        @if($canSetengah)
                            <button
                                wire:click="absenSetengah({{ $schedule->id }})"
                                style="
                                    padding: 1rem;
                                    border-radius: 0.5rem;
                                    font-size: 0.875rem;
                                    font-weight: 500;
                                    cursor: pointer;
                                    height: 3.5rem;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    gap: 0.5rem;
                                    transition: background-color 0.2s ease;
                                "
                                class="btn-warning"
                            >
                                🕐 Absen Setengah
                            </button>
                        @endif

                        <button
                            wire:click="absenPulang({{ $schedule->id }})"
                            {{ !$canPulang ? 'disabled' : '' }}
                            style="
                                padding: 1rem;
                                border-radius: 0.5rem;
                                font-size: 0.875rem;
                                font-weight: 500;
                                cursor: pointer;
                                height: 3.5rem;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                gap: 0.5rem;
                                transition: background-color 0.2s ease;
                            "
                            class="btn-info"
                        >
                            🚶 Absen Pulang
                        </button>

                        <button
                            wire:click="mountAction('izinAction', { schedule_id: {{ $schedule->id }} })"
                            {{ !$canIzin ? 'disabled' : '' }}
                            style="
                                padding: 1rem;
                                border-radius: 0.5rem;
                                font-size: 0.875rem;
                                font-weight: 500;
                                cursor: pointer;
                                height: 3.5rem;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                gap: 0.5rem;
                                transition: background-color 0.2s ease;
                            "
                            class="btn-gray"
                        >
                            📝 Izin
                        </button>
                    </div>

                    {{-- FOOTER NOTE --}}
                    <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid;" class="border-color">
                        <p class="text-secondary" style="font-size: 0.75rem; text-align: center;">
                            ⚠️ Pastikan GPS dan kamera aktif sebelum melakukan presensi
                        </p>
                    </div>
                </div>

            </div>

        @endforeach

    </div>

    {{-- ================= SCRIPT TETAP SAMA ================= --}}
    <script>
        function gpsTracker() {
            return {
                lat: null,
                lng: null,
                watchId: null,
                startTracking() {
                    if (!navigator.geolocation) return;
                    this.watchId = navigator.geolocation.watchPosition(p => {
                        this.lat = p.coords.latitude;
                        this.lng = p.coords.longitude;
                        @this.set('userLat', this.lat);
                        @this.set('userLng', this.lng);
                    });
                }
            }
        }

        function cameraMasuk() {
            return {
                photo: null, stream: null,
                init() {
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
                        .then(s => { this.stream = s; this.$refs.video.srcObject = s; });
                },
                capture() {
                    const c = document.createElement('canvas');
                    c.width = this.$refs.video.videoWidth;
                    c.height = this.$refs.video.videoHeight;
                    c.getContext('2d').drawImage(this.$refs.video, 0, 0);
                    this.photo = c.toDataURL('image/jpeg', 0.8);
                    this.stream.getTracks().forEach(t => t.stop());
                    @this.call('savePhotoMasuk', this.photo);
                },
                retake() {
                    this.photo = null;
                    @this.call('savePhotoMasuk', null);
                    this.init();
                }
            }
        }

        function cameraPulang() {
            return {
                photo: null, stream: null,
                init() {
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
                        .then(s => { this.stream = s; this.$refs.video.srcObject = s; });
                },
                capture() {
                    const c = document.createElement('canvas');
                    c.width = this.$refs.video.videoWidth;
                    c.height = this.$refs.video.videoHeight;
                    c.getContext('2d').drawImage(this.$refs.video, 0, 0);
                    this.photo = c.toDataURL('image/jpeg', 0.8);
                    this.stream.getTracks().forEach(t => t.stop());
                    @this.call('savePhotoPulang', this.photo);
                },
                retake() {
                    this.photo = null;
                    @this.call('savePhotoPulang', null);
                    this.init();
                }
            }
        }
    </script>

</x-filament-panels::page>
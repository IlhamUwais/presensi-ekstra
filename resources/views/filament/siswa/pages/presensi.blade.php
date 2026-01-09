<x-filament-panels::page>
    {{-- Container Utama: Style CSS Manual --}}
    <div style="width: 100%; max-width: 1100px; margin: 0 auto; font-family: sans-serif;">

        @foreach ($this->schedules as $schedule)
            @php
                $attendance = $this->getTodayAttendance($schedule);
                $canMasuk = $this->canAbsenMasuk($schedule);
                $canSetengah = $this->canAbsenSetengah($schedule);
                $canPulang = $this->canAbsenPulang($schedule);
                $canIzin = $this->canIzin($schedule);
            @endphp

            {{-- KARTU PUTIH --}}
            <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;">
                
                {{-- HEADER: Flexbox Manual --}}
                <div style="background: #f9fafb; padding: 16px 24px; border-bottom: 1px solid #e5e7eb; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 10px;">
                    <div>
                        <h2 style="font-size: 18px; font-weight: 700; color: #111827; margin: 0;">{{ $schedule->ekstra->name }}</h2>
                        <div style="font-size: 13px; color: #6b7280; margin-top: 4px;">
                            📍 {{ $schedule->roomEkstra->name }} &nbsp;|&nbsp; ⏰ {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </div>
                    </div>
                    
                    {{-- Status Badge --}}
                    <div>
                         @if($attendance)
                            <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;
                                background-color: {{ $attendance->status === 'hadir' ? '#d1fae5' : ($attendance->status === 'setengah' ? '#fef3c7' : '#fee2e2') }};
                                color: {{ $attendance->status === 'hadir' ? '#065f46' : ($attendance->status === 'setengah' ? '#92400e' : '#991b1b') }};">
                                {{ $attendance->status }}
                            </span>
                        @else
                            <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; background-color: #f3f4f6; color: #4b5563;">
                                Belum Absen
                            </span>
                        @endif
                    </div>
                </div>

                <div style="padding: 24px;">
                    
                    {{-- GRID SYSTEM MANUAL: INI YANG MEMBUAT BERJEJER --}}
                    {{-- Menggunakan CSS Grid agar responsif (sebelahan di PC, numpuk di HP) --}}
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px;">
                        
                        {{-- ==================== KOLOM KIRI (MASUK) ==================== --}}
                        <div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <label style="font-weight: 600; color: #374151; font-size: 14px;">📸 Kamera Masuk</label>
                                @if($attendance && $attendance->clock_in)
                                    <span style="background: #dcfce7; color: #166534; font-size: 12px; padding: 2px 6px; border-radius: 4px; font-family: monospace;">
                                        {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}
                                    </span>
                                @endif
                            </div>

                            {{-- Area Kamera Masuk --}}
                            <div x-data="cameraMasuk()" x-init="init()" style="position: relative; width: 100%; aspect-ratio: 16/9; background: #000; border-radius: 8px; overflow: hidden; border: 2px solid #e5e7eb;">
                                <video x-show="!photo" x-ref="video" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                                <img x-show="photo" :src="photo" style="width: 100%; height: 100%; object-fit: cover;" />
                                
                                {{-- Tombol Overlay --}}
                                <div style="position: absolute; bottom: 12px; left: 0; right: 0; display: flex; justify-content: center; gap: 10px; z-index: 10;">
                                    <button x-show="!photo" @click="capture" type="button" style="background: rgba(255,255,255,0.9); border: none; color: #000; padding: 6px 16px; border-radius: 20px; font-weight: bold; font-size: 12px; cursor: pointer;">📷 Jepret</button>
                                    <button x-show="photo" @click="retake" type="button" style="background: rgba(239, 68, 68, 0.9); border: none; color: white; padding: 6px 16px; border-radius: 20px; font-weight: bold; font-size: 12px; cursor: pointer;">🔄 Ulang</button>
                                </div>
                            </div>

                            {{-- Tombol Kirim Masuk --}}
                            <div style="margin-top: 12px;">
                                @if($canMasuk)
                                    <button wire:click="absenMasuk({{ $schedule->id }})" style="width: 100%; background-color: #10b981; color: white; padding: 12px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; transition: background 0.2s;">
                                        ✅ Kirim Absen Masuk
                                    </button>
                                @else
                                    <div style="width: 100%; text-align: center; color: #9ca3af; padding: 10px; background: #f9fafb; border: 1px dashed #d1d5db; border-radius: 8px; font-size: 13px;">
                                        @if($attendance) Sudah Absen @else Belum Waktunya @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- ==================== KOLOM KANAN (PULANG) ==================== --}}
                        <div>
                             <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <label style="font-weight: 600; color: #374151; font-size: 14px;">📸 Kamera Pulang</label>
                                @if($attendance && $attendance->clock_out)
                                    <span style="background: #ffedd5; color: #9a3412; font-size: 12px; padding: 2px 6px; border-radius: 4px; font-family: monospace;">
                                        {{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}
                                    </span>
                                @endif
                            </div>

                            {{-- Area Kamera Pulang --}}
                            <div x-data="cameraPulang()" x-init="init()" style="position: relative; width: 100%; aspect-ratio: 16/9; background: #000; border-radius: 8px; overflow: hidden; border: 2px solid #e5e7eb;">
                                <video x-show="!photo" x-ref="video" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                                <img x-show="photo" :src="photo" style="width: 100%; height: 100%; object-fit: cover;" />
                                
                                {{-- Tombol Overlay --}}
                                <div style="position: absolute; bottom: 12px; left: 0; right: 0; display: flex; justify-content: center; gap: 10px; z-index: 10;">
                                    <button x-show="!photo" @click="capture" type="button" style="background: rgba(255,255,255,0.9); border: none; color: #000; padding: 6px 16px; border-radius: 20px; font-weight: bold; font-size: 12px; cursor: pointer;">📷 Jepret</button>
                                    <button x-show="photo" @click="retake" type="button" style="background: rgba(239, 68, 68, 0.9); border: none; color: white; padding: 6px 16px; border-radius: 20px; font-weight: bold; font-size: 12px; cursor: pointer;">🔄 Ulang</button>
                                </div>
                            </div>

                            {{-- Tombol Kirim Pulang --}}
                            <div style="margin-top: 12px;">
                                <button 
                                    wire:click="absenPulang({{ $schedule->id }})" 
                                    @if(!$canPulang) disabled @endif
                                    style="width: 100%; background-color: {{ $canPulang ? '#f97316' : '#e5e7eb' }}; color: {{ $canPulang ? 'white' : '#9ca3af' }}; padding: 12px; border-radius: 8px; font-weight: 600; border: none; cursor: {{ $canPulang ? 'pointer' : 'not-allowed' }}; transition: background 0.2s;">
                                    🏠 Kirim Absen Pulang
                                </button>
                            </div>
                        </div>

                    </div>
                    {{-- END GRID --}}

                    {{-- Footer: Izin & Setengah --}}
                    <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #f3f4f6; display: flex; gap: 10px;">
                        @if($canSetengah)
                            <button wire:click="absenSetengah({{ $schedule->id }})" style="background: #fffbeb; color: #b45309; border: 1px solid #fcd34d; padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 13px;">
                                ⚠️ Absen Setengah Hari
                            </button>
                        @endif
                         <button wire:click="mountAction('izinAction', { schedule_id: {{ $schedule->id }} })" @if(!$canIzin) disabled @endif style="background: #f9fafb; color: #4b5563; border: 1px solid #d1d5db; padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 13px;">
                            📝 Ajukan Izin
                        </button>
                    </div>

                    {{-- GPS Minimalis --}}
                    <div x-data="gpsTracker()" x-init="startTracking()" style="margin-top: 10px; font-size: 11px; color: #9ca3af; text-align: right;">
                        <span x-show="!lat">📡 Mencari lokasi...</span>
                        <span x-show="lat" style="color: #10b981;">📡 Lokasi Terkunci</span>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    {{-- SCRIPT JAVASCRIPT (SAMA) --}}
    <script>
        function cameraMasuk() {
            return {
                photo: null, stream: null,
                init() {
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } }).then(s => { this.stream = s; this.$refs.video.srcObject = s; });
                },
                capture() {
                    let v = this.$refs.video; let c = document.createElement('canvas'); c.width = v.videoWidth; c.height = v.videoHeight;
                    c.getContext('2d').drawImage(v, 0, 0); this.photo = c.toDataURL('image/jpeg', 0.8);
                    @this.call('savePhotoMasuk', this.photo);
                },
                retake() { this.photo = null; @this.call('savePhotoMasuk', null); }
            }
        }
        function cameraPulang() {
            return {
                photo: null, stream: null,
                init() {
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } }).then(s => { this.stream = s; this.$refs.video.srcObject = s; });
                },
                capture() {
                    let v = this.$refs.video; let c = document.createElement('canvas'); c.width = v.videoWidth; c.height = v.videoHeight;
                    c.getContext('2d').drawImage(v, 0, 0); this.photo = c.toDataURL('image/jpeg', 0.8);
                    @this.call('savePhotoPulang', this.photo);
                },
                retake() { this.photo = null; @this.call('savePhotoPulang', null); }
            }
        }
        function gpsTracker() {
             return {
                lat: null, lng: null, watchId: null,
                startTracking() {
                    if (!navigator.geolocation) return;
                    this.watchId = navigator.geolocation.watchPosition(
                        p => { 
                            this.lat = p.coords.latitude; this.lng = p.coords.longitude;
                            @this.set('userLat', this.lat); @this.set('userLng', this.lng);
                        },
                        e => console.error(e),
                        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
                    );
                }
            }
        }
    </script>
</x-filament-panels::page>
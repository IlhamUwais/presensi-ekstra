<x-filament-panels::page>
    <div class="space-y-6">
        @foreach ($this->schedules as $schedule)
            @php
                $attendance = $this->getTodayAttendance($schedule);
                $canMasuk = $this->canAbsenMasuk($schedule);
                $canSetengah = $this->canAbsenSetengah($schedule);
                $canPulang = $this->canAbsenPulang($schedule);
                $canIzin = $this->canIzin($schedule);
            @endphp

            <div class="p-6 bg-white rounded-xl border shadow-sm">
                {{-- Header --}}
                <div class="mb-4">
                    <h3 class="font-bold text-lg">{{ $schedule->ekstra->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $schedule->roomEkstra->name }}</p>
                    <p class="text-xs text-gray-400">
                        ⏰ {{ $schedule->start_time }} - {{ $schedule->end_time }}
                    </p>
                </div>

                {{-- GPS Status Indicator --}}
                <div class="mb-3">
                    <div x-data="gpsTracker()" x-init="startTracking()">
                        <div class="flex items-center gap-2">
                           <span class="text-xs"
                    :class="lat && lng ? 'text-green-600' : 'text-red-600'">
                    <span x-show="lat && lng">📍 GPS Aktif</span>
                    <span x-show="!lat || !lng">📍 GPS Tidak Aktif</span>
                </span>

                            <button 
                                x-show="!isTracking" 
                                @click="startTracking"
                                class="text-xs underline text-blue-600 hover:text-blue-800"
                            >
                                Aktifkan GPS
                            </button>
                        </div>
                        <p class="text-xs text-gray-500" x-show="isTracking && lat && lng">
                            Lokasi: <span x-text="lat?.toFixed(6)"></span>, <span x-text="lng?.toFixed(6)"></span>
                        </p>
                    </div>
                </div>

                {{-- Status Badge --}}
                <div class="mb-4">
                    @if($attendance)
                        <x-filament::badge 
                            color="{{ 
                                $attendance->status === 'hadir' ? 'success' : 
                                ($attendance->status === 'setengah' ? 'warning' : 'danger')
                            }}"
                        >
                            {{ strtoupper($attendance->status) }}
                        </x-filament::badge>
                        
                        @if($attendance->clock_in)
                            <span class="text-xs text-gray-500 ml-2">
                                Masuk: {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}
                            </span>
                        @endif
                        
                        @if($attendance->clock_out)
                            <span class="text-xs text-gray-500 ml-2">
                                Pulang: {{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}
                            </span>
                        @endif
                    @else
                        <x-filament::badge color="gray">
                            BELUM ABSEN
                        </x-filament::badge>
                    @endif
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    {{-- CAMERA MASUK --}}
                    <div class="border rounded-lg p-4 bg-green-50">
                        <h4 class="font-semibold text-sm mb-3 text-green-800">📷 Camera Masuk</h4>
                        <div x-data="cameraMasuk()" x-init="init()" class="space-y-3">
                            <video 
                                x-show="!photo" 
                                x-ref="video" 
                                autoplay 
                                playsinline
                                class="rounded w-full border border-green-200"
                            ></video>
                            
                            <img 
                                x-show="photo" 
                                :src="photo" 
                                class="rounded w-full border border-green-200"
                            />

                            <div class="flex gap-2">
                                <x-filament::button 
                                    x-show="!photo" 
                                    @click="capture"
                                    color="success"
                                    size="sm"
                                >
                                    📷 Ambil
                                </x-filament::button>
                                
                                <x-filament::button 
                                    x-show="photo" 
                                    @click="retake"
                                    color="gray"
                                    size="sm"
                                >
                                    🔄 Ulangi
                                </x-filament::button>
                            </div>
                        </div>
                    </div>

                    {{-- CAMERA PULANG --}}
                    <div class="border rounded-lg p-4 bg-orange-50">
                        <h4 class="font-semibold text-sm mb-3 text-orange-800">📷 Camera Pulang</h4>
                        <div x-data="cameraPulang()" x-init="init()" class="space-y-3">
                            <video 
                                x-show="!photo" 
                                x-ref="video" 
                                autoplay 
                                playsinline
                                class="rounded w-full border border-orange-200"
                            ></video>
                            
                            <img 
                                x-show="photo" 
                                :src="photo" 
                                class="rounded w-full border border-orange-200"
                            />

                            <div class="flex gap-2">
                                <x-filament::button 
                                    x-show="!photo" 
                                    @click="capture"
                                    color="warning"
                                    size="sm"
                                >
                                    📷 Ambil
                                </x-filament::button>
                                
                                <x-filament::button 
                                    x-show="photo" 
                                    @click="retake"
                                    color="gray"
                                    size="sm"
                                >
                                    🔄 Ulangi
                                </x-filament::button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-4 space-y-2">
                    {{-- Baris 1: Masuk / Setengah --}}
                    <div class="flex gap-2 flex-wrap">
                      @if($canMasuk)
                        <x-filament::button wire:click="absenMasuk({{ $schedule->id }})" color="success">
                            ✅ Absen Masuk
                        </x-filament::button>
                    @endif

                    @if($canSetengah)
                        <x-filament::button wire:click="absenSetengah({{ $schedule->id }})" color="warning">
                            ⚠️ Absen Setengah
                        </x-filament::button>
                    @endif


                        <x-filament::button
                            wire:click="absenPulang({{ $schedule->id }})"
                            color="info"
                            :disabled="!$canPulang"
                        >
                            🏠 Absen Pulang
                        </x-filament::button>
                    </div>

                    {{-- Baris 2: Izin (Terpisah) --}}
                    <div>
                        <x-filament::button
                            wire:click="mountAction('izinAction', { schedule_id: {{ $schedule->id }} })"
                            color="gray"
                            :disabled="!$canIzin"
                        >
                            📝 Izin
                        </x-filament::button>
                        <!-- Contoh Baru -->

                    </div>
                </div>

                {{-- Debug Info --}}
                <div class="mt-4 p-3 bg-gray-50 rounded text-xs space-y-1">
                    <strong>🔍 Debug Info:</strong>
                    <div class="grid grid-cols-2 gap-2">
                        <div>Waktu Sekarang: <strong>{{ now()->format('H:i:s') }}</strong></div>
                        <div>Status: <strong>{{ $attendance->status ?? 'BELUM ABSEN' }}</strong></div>
                        <div>User GPS: <span x-data x-text="@this.userLat + ', ' + @this.userLng"></span></div>
                        <div>Room GPS: {{ $schedule->roomEkstra->latitude ?? 'N/A' }}, {{ $schedule->roomEkstra->longitude ?? 'N/A' }}</div>
                        <div>Radius: {{ $schedule->roomEkstra->radius ?? 50 }}m</div>
                        <div>Jam Ekstra: {{ $schedule->start_time }} - {{ $schedule->end_time }}</div>
                    </div>
                    <div class="pt-2 border-t">
                        <div class="flex gap-4">
                            <span class="text-{{ $canMasuk ? 'green' : 'red' }}-600">
                                Masuk: {{ $canMasuk ? '✓' : '✗' }}
                            </span>
                            <span class="text-{{ $canSetengah ? 'orange' : 'red' }}-600">
                                Setengah: {{ $canSetengah ? '✓' : '✗' }}
                            </span>
                            <span class="text-{{ $canPulang ? 'green' : 'red' }}-600">
                                Pulang: {{ $canPulang ? '✓' : '✗' }}
                            </span>
                            <span class="text-{{ $canIzin ? 'green' : 'red' }}-600">
                                Izin: {{ $canIzin ? '✓' : '✗' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if($this->schedules->isEmpty())
            <div class="p-6 bg-white rounded-xl border text-center text-gray-500">
                📅 Tidak ada jadwal ekskul hari ini
            </div>
        @endif
    </div>

    <script>
        // GPS Tracker Component
       function gpsTracker() {
    return {
        lat: null,
        lng: null,
        watchId: null,

        startTracking() {
            if (!navigator.geolocation) {
                alert('Browser tidak mendukung GPS');
                return;
            }

            this.watchId = navigator.geolocation.watchPosition(
                (position) => {
                    this.lat = position.coords.latitude;
                    this.lng = position.coords.longitude;

                    @this.set('userLat', this.lat);
                    @this.set('userLng', this.lng);

                    console.log('GPS OK:', this.lat, this.lng);
                },
                (error) => {
                    console.error(error);
                    alert('❌ GPS gagal. Aktifkan lokasi & refresh halaman.');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        },

        stopTracking() {
            if (this.watchId) {
                navigator.geolocation.clearWatch(this.watchId);
                this.watchId = null;
            }
            this.lat = null;
            this.lng = null;
        }
    }
}

        // Camera MASUK Component
        function cameraMasuk() {
            return {
                photo: null,
                stream: null,
                
                init() {
                    navigator.mediaDevices.getUserMedia({ 
                        video: { 
                            facingMode: 'user',
                            width: { ideal: 1280 },
                            height: { ideal: 720 }
                        } 
                    })
                    .then(s => {
                        this.stream = s;
                        this.$refs.video.srcObject = s;
                    })
                    .catch(err => {
                        console.error('Camera Masuk error:', err);
                    });
                },
                
                capture() {
                    const video = this.$refs.video;
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0);
                    this.photo = canvas.toDataURL('image/jpeg', 0.8);
                    
                    if (this.stream) {
                        this.stream.getTracks().forEach(t => t.stop());
                    }
                    
                    @this.call('savePhotoMasuk', this.photo);
                },
                
                retake() {
                    this.photo = null;
                    @this.call('savePhotoMasuk', null);
                    this.init();
                }
            }
        }

        // Camera PULANG Component
        function cameraPulang() {
            return {
                photo: null,
                stream: null,
                
                init() {
                    navigator.mediaDevices.getUserMedia({ 
                        video: { 
                            facingMode: 'user',
                            width: { ideal: 1280 },
                            height: { ideal: 720 }
                        } 
                    })
                    .then(s => {
                        this.stream = s;
                        this.$refs.video.srcObject = s;
                    })
                    .catch(err => {
                        console.error('Camera Pulang error:', err);
                    });
                },
                
                capture() {
                    const video = this.$refs.video;
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0);
                    this.photo = canvas.toDataURL('image/jpeg', 0.8);
                    
                    if (this.stream) {
                        this.stream.getTracks().forEach(t => t.stop());
                    }
                    
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
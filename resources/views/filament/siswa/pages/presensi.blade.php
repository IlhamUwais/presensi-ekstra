<x-filament-panels::page>
    <div class="space-y-6">
        @foreach ($this->schedules as $schedule)
            @php
                $attendance = $this->getTodayAttendance($schedule);
                $canMasuk = $this->canAbsenMasuk($schedule);
                $canPulang = $this->canAbsenPulang($schedule);
                $canIzin = $this->canIzin($schedule);
            @endphp

            <div class="p-6 bg-white rounded-xl border">
                <h3 class="font-bold text-lg">{{ $schedule->ekstra->name }}</h3>
                <p class="text-sm text-gray-500">{{ $schedule->roomEkstra->name }}</p>
                <p class="text-xs text-gray-400">
                    {{ $schedule->start_time }} - {{ $schedule->end_time }}
                </p>

                {{-- GPS Status Indicator --}}
                <div class="my-3 flex items-center gap-2">
                    <div x-data="gpsTracker()" x-init="startTracking()">
                        <div class="flex items-center gap-2">
                            <span class="text-xs" :class="isTracking ? 'text-green-600' : 'text-red-600'">
                                <span x-show="isTracking">📍 GPS Aktif</span>
                                <span x-show="!isTracking">📍 GPS Tidak Aktif</span>
                            </span>
                            <button 
                                x-show="!isTracking" 
                                @click="startTracking"
                                class="text-xs underline text-blue-600"
                            >
                                Aktifkan GPS
                            </button>
                        </div>
                        <p class="text-xs text-gray-500" x-show="isTracking && lat && lng">
                            Lokasi: <span x-text="lat?.toFixed(6)"></span>, <span x-text="lng?.toFixed(6)"></span>
                        </p>
                    </div>
                </div>

                <div class="my-3">
                    @if($attendance)
                        <x-filament::badge color="{{ $attendance->status === 'hadir' ? 'success' : 'warning' }}">
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

                {{-- Camera Component --}}
                <div x-data="camera()" x-init="init()" class="space-y-3">
                    <video 
                        x-show="!photo" 
                        x-ref="video" 
                        autoplay 
                        playsinline
                        class="rounded w-full max-w-md border"
                    ></video>
                    
                    <img 
                        x-show="photo" 
                        :src="photo" 
                        class="rounded w-full max-w-md border"
                    />

                    <div class="flex gap-2">
                        <x-filament::button 
                            x-show="!photo" 
                            @click="capture"
                            color="primary"
                        >
                            📷 Ambil Foto
                        </x-filament::button>
                        
                        <x-filament::button 
                            x-show="photo" 
                            @click="retake"
                            color="gray"
                        >
                            🔄 Ulangi Foto
                        </x-filament::button>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2 mt-4 flex-wrap">
                    <x-filament::button
                        wire:click="absenMasuk({{ $schedule->id }})"
                        color="success"
                        :disabled="!$canMasuk"
                    >
                        ✅ Absen Masuk
                    </x-filament::button>

                    <x-filament::button
                        wire:click="absenPulang({{ $schedule->id }})"
                        color="warning"
                        :disabled="!$canPulang"
                    >
                        🏠 Absen Pulang
                    </x-filament::button>

                    <x-filament::button
                        wire:click="izin({{ $schedule->id }})"
                        color="gray"
                        :disabled="!$canIzin"
                    >
                        📝 Izin
                    </x-filament::button>
                </div>

                {{-- Debug Info (Hapus setelah selesai testing) --}}
                <div class="mt-3 p-2 bg-gray-50 rounded text-xs">
                    <strong>Debug Info:</strong><br>
                    Waktu Sekarang: {{ now()->format('H:i:s') }}<br>
                    User GPS: <span x-data x-text="@this.userLat + ', ' + @this.userLng"></span><br>
                    Room GPS: {{ $schedule->roomEkstra->latitude ?? 'N/A' }}, {{ $schedule->roomEkstra->longitude ?? 'N/A' }}<br>
                    Radius: {{ $schedule->roomEkstra->radius ?? 50 }}m<br>
                    Boleh Masuk: {{ $canMasuk ? 'YA' : 'TIDAK' }}<br>
                    Boleh Pulang: {{ $canPulang ? 'YA' : 'TIDAK' }}<br>
                    Boleh Izin: {{ $canIzin ? 'YA' : 'TIDAK' }}
                </div>
            </div>
        @endforeach

        @if($this->schedules->isEmpty())
            <div class="p-6 bg-white rounded-xl border text-center text-gray-500">
                Tidak ada jadwal ekskul hari ini
            </div>
        @endif
    </div>

    <script>
        // GPS Tracker Component
        function gpsTracker() {
            return {
                isTracking: false,
                lat: null,
                lng: null,
                watchId: null,
                
                startTracking() {
                    if (!navigator.geolocation) {
                        alert('Browser Anda tidak mendukung GPS');
                        return;
                    }

                    // Request permission dan mulai tracking
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.updatePosition(position);
                            this.isTracking = true;
                            
                            // Watch position untuk update real-time
                            this.watchId = navigator.geolocation.watchPosition(
                                (pos) => this.updatePosition(pos),
                                (error) => this.handleError(error),
                                {
                                    enableHighAccuracy: true,
                                    timeout: 1000000,
                                    maximumAge: 0
                                }
                            );
                        },
                        (error) => this.handleError(error),
                        {
                            enableHighAccuracy: true,
                            timeout: 1000000,
                            maximumAge: 0
                        }
                    );
                },
                
                updatePosition(position) {
                    this.lat = position.coords.latitude;
                    this.lng = position.coords.longitude; // Pastikan 'longitude' bukan 'longtitude'
                    
                    // Send ke Livewire
                    @this.set('userLat', this.lat);
                    @this.set('userLng', this.lng);
                    
                    console.log('GPS Updated:', {
                        latitude: this.lat,
                        longitude: this.lng,
                        accuracy: position.coords.accuracy
                    });
                },
                
                handleError(error) {
                    this.isTracking = false;
                    
                    let message = 'Gagal mengakses GPS';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            message = '❌ Izin GPS ditolak. Silakan aktifkan di pengaturan browser.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message = '❌ Lokasi tidak tersedia. Pastikan GPS perangkat aktif.';
                            break;
                        case error.TIMEOUT:
                            message = '❌ Waktu habis saat mencari lokasi.';
                            break;
                    }
                    
                    alert(message);
                    console.error('GPS Error:', error);
                },
                
                stopTracking() {
                    if (this.watchId) {
                        navigator.geolocation.clearWatch(this.watchId);
                        this.watchId = null;
                    }
                    this.isTracking = false;
                }
            }
        }

        // Camera Component
        function camera() {
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
                        console.error('Camera error:', err);
                        alert('Tidak bisa mengakses kamera. Pastikan izin kamera sudah diberikan.');
                    });
                },
                
                capture() {
                    const video = this.$refs.video;
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0);
                    this.photo = canvas.toDataURL('image/jpeg', 0.8);
                    
                    // Stop camera stream
                    if (this.stream) {
                        this.stream.getTracks().forEach(t => t.stop());
                    }
                    
                    // Send to Livewire
                    @this.call('savePhoto', this.photo);
                },
                
                retake() {
                    this.photo = null;
                    @this.call('savePhoto', null);
                    this.init();
                }
            }
        }
    </script>
</x-filament-panels::page>
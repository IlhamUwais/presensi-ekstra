<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Presensi Ekstrakurikuler</title>
    
    <!-- Load CSS dengan Vite -->
    @vite(['resources/css/welcome.css'])
    
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="logo">🔧</div>
            <h1>Sistem Presensi Ekstrakurikuler</h1>
            <p class="subtitle">Website terpadu untuk manajemen kehadiran kegiatan ekstrakurikuler sekolah</p>
        </div>

        <!-- Role Selection Grid -->
        <div class="roles-grid">
            <a href="/admin/login" class="role-card admin">
                <div class="role-icon">⚙️</div>
                <div class="role-name">Administrator</div>
                <div class="role-desc">Kelola sistem, data pengguna, dan laporan lengkap presensi</div>
            </a>
            
            <a href="/siswa/login" class="role-card siswa">
                <div class="role-icon">🎓</div>
                <div class="role-name">Siswa</div>
                <div class="role-desc">Presensi ekskul, lihat jadwal, dan riwayat kehadiran</div>
            </a>
            
            <a href="/pembina/login" class="role-card pembina">
                <div class="role-icon">👨‍🏫</div>
                <div class="role-name">Pembina</div>
                <div class="role-desc">Kelola presensi, input nilai, dan pantau perkembangan peserta</div>
            </a>
        </div>

        <!-- Daftar Ekstrakurikuler Section -->
        <div class="ekskul-section">
            <h2 class="section-title">Daftar Ekstrakurikuler</h2>
            <p class="section-subtitle">Berikut adalah daftar ekstrakurikuler yang tersedia di sekolah kami. Setiap ekskul memiliki pembina dan jadwal kegiatan.</p>
            
            <div class="ekskul-list">
                @forelse($ekstras as $ekstra)
                <div class="ekskul-item">
                    <div class="ekskul-info">
                        <div class="ekskul-icon">📋</div>
                        <div class="ekskul-details">
                            <div class="ekskul-name">{{ $ekstra->name }}</div>
                            <div class="ekskul-pembina">
                                Pembina: {{ $ekstra->pembina->name ?? 'Belum ditentukan' }}
                            </div>
                            
                            <!-- Jadwal Ekskul -->
                            <div class="ekskul-schedule">
                                @foreach($ekstra->schedules as $schedule)
                                <div class="schedule-item">
                                    <span class="schedule-day">{{ $schedule->day_of_week }}</span>
                                    <span class="schedule-time">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </span>
                                    @if($schedule->roomEkstra)
                                    <span class="schedule-room">({{ $schedule->roomEkstra->name }})</span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="ekskul-stats">
                        <div class="stat-item">
                            @if($ekstra->schedules->isNotEmpty())
                                <div class="stat-value">
                                    {{ ucfirst($ekstra->schedules->first()->day_of_week) }}
                                </div>
                            @else
                                <div class="stat-value">-</div>
                            @endif
                            <div class="stat-label">Hari</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="ekskul-item">
                    <div class="ekskul-info">
                        <div class="ekskul-icon">📋</div>
                        <div class="ekskul-details">
                            <div class="ekskul-name">Belum ada ekstrakurikuler</div>
                            <div class="ekskul-pembina">Silakan hubungi administrator</div>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <h3><span>ℹ️</span> Panduan Akses Sistem</h3>
            <ul>
                <li>Gunakan <strong>URL HTTPS</strong> untuk akses fitur presensi lokasi/GPS</li>
                <li><strong>Administrator:</strong> admin@sekolah.sch.id | password: admin123</li>
                <li><strong>Siswa:</strong> NIS  | password default: nis12345</li>
                <li><strong>Pembina:</strong> NIS terdaftar | password default: pembina123</li>
                <li>Izinkan akses lokasi pada browser untuk presensi berbasis GPS</li>
                <li>Pastikan koneksi internet stabil saat melakukan presensi</li>
            </ul>
            
            <div class="footer-note">
                Sistem Presensi Ekstrakurikuler v1.0 • © 2024 Sekolah
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add click effect to role cards
            const roleCards = document.querySelectorAll('.role-card');
            
            roleCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Create ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.4);
                        transform: scale(0);
                        animation: ripple 0.6s ease-out;
                        width: ${size}px;
                        height: ${size}px;
                        top: ${y}px;
                        left: ${x}px;
                        pointer-events: none;
                        z-index: 1;
                    `;
                    
                    this.appendChild(ripple);
                    
                    // Remove ripple after animation
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                    
                    // Navigate after delay
                    setTimeout(() => {
                        window.location.href = this.href;
                    }, 300);
                });
                
                // Hover effect for icons
                card.addEventListener('mouseenter', function() {
                    const icon = this.querySelector('.role-icon');
                    icon.style.transform = 'scale(1.3) rotate(5deg)';
                });
                
                card.addEventListener('mouseleave', function() {
                    const icon = this.querySelector('.role-icon');
                    icon.style.transform = 'scale(1) rotate(0deg)';
                });
            });
            
            // Add hover effect to ekskul items
            const ekskulItems = document.querySelectorAll('.ekskul-item');
            
            ekskulItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(5px)';
                    this.style.borderLeftColor = '#fff';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                    this.style.borderLeftColor = 'transparent';
                });
            });
        });
    </script>
</body>
</html>
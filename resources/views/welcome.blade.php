<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Presensi Ekstrakurikuler</title>
    <style>
        /* Reset & Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #2a2a2a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #ffffff;
            position: relative;
            overflow-x: hidden;
        }

        /* Elegant Grid Pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: -1;
            opacity: 0.3;
        }

        /* Subtle Particle Effect */
        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 10% 20%, rgba(255,255,255,0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(255,255,255,0.05) 0%, transparent 40%);
            z-index: -1;
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        @keyframes borderGlow {
            0%, 100% { border-color: rgba(255,255,255,0.1); }
            50% { border-color: rgba(255,255,255,0.3); }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .container {
            text-align: center;
            max-width: 1200px;
            width: 100%;
            animation: fadeInUp 0.8s ease-out;
        }

        /* Header Section */
        .header {
            margin-bottom: 50px;
        }

        .logo {
            font-size: 64px;
            margin-bottom: 25px;
            animation: float 8s ease-in-out infinite;
            display: inline-block;
            background: linear-gradient(90deg, #fff, #aaa, #fff);
            background-size: 200% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s infinite linear;
        }

        h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 16px;
            background: linear-gradient(90deg, #ffffff, #cccccc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 1.2rem;
            color: #aaa;
            max-width: 600px;
            margin: 0 auto 40px;
            line-height: 1.6;
            font-weight: 300;
        }

        /* Role Cards Grid */
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .role-card {
            background: rgba(20, 20, 20, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 32px 24px;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.02), transparent);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .role-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            border-color: rgba(255,255,255,0.3);
        }

        .role-card:hover::before {
            opacity: 1;
        }

        /* Card Specific Colors with Monochrome */
        .role-card.admin {
            --accent-color: #ffffff;
            border-top: 4px solid #ffffff;
        }

        .role-card.siswa {
            --accent-color: #cccccc;
            border-top: 4px solid #cccccc;
        }

        .role-card.pembina {
            --accent-color: #999999;
            border-top: 4px solid #999999;
        }

        .role-icon {
            font-size: 48px;
            margin-bottom: 20px;
            display: inline-block;
            transition: all 0.4s ease;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        }

        .role-card:hover .role-icon {
            transform: scale(1.3) rotate(5deg);
            filter: drop-shadow(0 6px 12px rgba(255,255,255,0.2));
        }

        .role-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-color, #fff);
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .role-desc {
            color: #888;
            font-size: 0.95rem;
            line-height: 1.5;
            font-weight: 300;
        }

        /* Daftar Ekstrakurikuler Section - DIUBAH */
        .ekskul-section {
            background: rgba(25, 25, 25, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            margin: 60px 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
            animation: fadeInUp 0.8s ease-out 0.3s both;
        }

        .section-title {
            font-size: 2rem;
            color: #fff;
            margin-bottom: 35px;
            font-weight: 700;
            position: relative;
            display: inline-block;
            text-align: left;
            width: 100%;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, #fff, #888);
            border-radius: 2px;
        }

        .section-subtitle {
            color: #aaa;
            font-size: 1rem;
            margin-bottom: 30px;
            text-align: left;
            font-weight: 300;
            line-height: 1.5;
        }

        /* Daftar Ekskul dalam List - DIUBAH */
        .ekskul-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .ekskul-item {
            background: rgba(40, 40, 40, 0.8);
            border-radius: 12px;
            padding: 20px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            animation: slideIn 0.5s ease-out;
            animation-fill-mode: both;
        }

        .ekskul-item:hover {
            background: rgba(50, 50, 50, 0.9);
            transform: translateX(5px);
            border-left-color: #fff;
        }

        .ekskul-item:nth-child(1) { animation-delay: 0.1s; }
        .ekskul-item:nth-child(2) { animation-delay: 0.2s; }
        .ekskul-item:nth-child(3) { animation-delay: 0.3s; }
        .ekskul-item:nth-child(4) { animation-delay: 0.4s; }
        .ekskul-item:nth-child(5) { animation-delay: 0.5s; }
        .ekskul-item:nth-child(6) { animation-delay: 0.6s; }
        .ekskul-item:nth-child(7) { animation-delay: 0.7s; }
        .ekskul-item:nth-child(8) { animation-delay: 0.8s; }

        .ekskul-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }

        .ekskul-icon {
            font-size: 24px;
            background: rgba(255,255,255,0.1);
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .ekskul-item:hover .ekskul-icon {
            background: rgba(255,255,255,0.15);
            transform: scale(1.1);
        }

        .ekskul-details {
            text-align: left;
        }

        .ekskul-name {
            font-weight: 600;
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: 4px;
        }

        .ekskul-pembina {
            color: #aaa;
            font-size: 0.9rem;
            font-weight: 300;
        }

        .ekskul-stats {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .stat-value {
            font-weight: 700;
            color: #fff;
            font-size: 1.2rem;
        }

        .stat-label {
            color: #888;
            font-size: 0.8rem;
            font-weight: 300;
        }

        .divider {
            width: 1px;
            height: 30px;
            background: rgba(255,255,255,0.1);
        }

        /* Info Box */
        .info-box {
            background: rgba(20, 20, 20, 0.9);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 30px;
            margin-top: 50px;
            text-align: left;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            animation: fadeInUp 0.8s ease-out 0.6s both;
            position: relative;
            overflow: hidden;
        }

        .info-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #fff, #888, #fff);
            animation: shimmer 2s infinite linear;
        }

        .info-box h3 {
            color: #fff;
            margin-bottom: 20px;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
        }

        .info-box h3 span {
            font-size: 1.6rem;
        }

        .info-box ul {
            list-style: none;
        }

        .info-box li {
            color: #bbb;
            margin-bottom: 12px;
            padding-left: 28px;
            position: relative;
            line-height: 1.5;
            font-size: 0.95rem;
        }

        .info-box li::before {
            content: '▸';
            position: absolute;
            left: 0;
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
            animation: borderGlow 2s infinite;
        }

        .info-box strong {
            color: #fff;
            font-weight: 600;
        }

        /* Footer Note */
        .footer-note {
            margin-top: 30px;
            color: #666;
            font-size: 0.9rem;
            font-style: italic;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.2rem;
            }
            
            .roles-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .role-card {
                padding: 28px 20px;
            }
            
            .ekskul-section {
                padding: 25px;
                margin: 40px 0;
            }
            
            .section-title {
                font-size: 1.7rem;
            }
            
            .ekskul-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
                padding: 20px;
            }
            
            .ekskul-stats {
                width: 100%;
                justify-content: space-between;
            }
            
            .divider {
                display: none;
            }
            
            .container {
                padding: 15px;
            }
            
            .logo {
                font-size: 52px;
            }
        }

        @media (max-width: 480px) {
            .logo {
                font-size: 44px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .ekskul-section {
                padding: 20px;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
            
            .ekskul-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .info-box {
                padding: 20px;
            }
        }

        /* Hover Ripple Effect */
        .role-card {
            position: relative;
            overflow: hidden;
        }

        .role-card::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.3);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .role-card:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(40, 40);
                opacity: 0;
            }
        }
    </style>
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="logo">📚</div>
            <h1>Sistem Presensi Ekstrakurikuler</h1>
            <p class="subtitle">Portal terpadu untuk manajemen kehadiran kegiatan ekstrakurikuler sekolah</p>
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

        <!-- Daftar Ekstrakurikuler Section - DIUBAH -->
        <div class="ekskul-section">
            <h2 class="section-title">Daftar Ekstrakurikuler</h2>
            <p class="section-subtitle">Berikut adalah daftar ekstrakurikuler yang tersedia di sekolah kami. Setiap ekskul memiliki pembina dan peserta aktif.</p>
            
            <div class="ekskul-list">
                <div class="ekskul-item">
                    <div class="ekskul-info">
                        <div class="ekskul-icon">🎯</div>
                        <div class="ekskul-details">
                            <div class="ekskul-name">Paskibra</div>
                            <div class="ekskul-pembina">Pembina: Bpk. Ahmad Sudirman</div>
                        </div>
                    </div>
                    <div class="ekskul-stats">
                        <div class="stat-item">
                            <div class="stat-value">45</div>
                            <div class="stat-label">Peserta</div>
                        </div>
                        <div class="divider"></div>
                        <div class="stat-item">
                            <div class="stat-value">Senin</div>
                            <div class="stat-label">Hari</div>
                        </div>
                    </div>
                </div>
                
                <div class="ekskul-item">
                    <div class="ekskul-info">
                        <div class="ekskul-icon">⚽</div>
                        <div class="ekskul-details">
                            <div class="ekskul-name">Futsal</div>
                            <div class="ekskul-pembina">Pembina: Bpk. Budi Santoso</div>
                        </div>
                    </div>
                    <div class="ekskul-stats">
                        <div class="stat-item">
                            <div class="stat-value">32</div>
                            <div class="stat-label">Peserta</div>
                        </div>
                        <div class="divider"></div>
                        <div class="stat-item">
                            <div class="stat-value">Selasa</div>
                            <div class="stat-label">Hari</div>
                        </div>
                    </div>
                </div>
                
                <div class="ekskul-item">
                    <div class="ekskul-info">
                        <div class="ekskul-icon">🏕️</div>
                        <div class="ekskul-details">
                            <div class="ekskul-name">Pramuka</div>
                            <div class="ekskul-pembina">Pembina: Ibu Siti Rahayu</div>
                        </div>
                    </div>
                    <div class="ekskul-stats">
                        <div class="stat-item">
                            <div class="stat-value">60</div>
                            <div class="stat-label">Peserta</div>
                        </div>
                        <div class="divider"></div>
                        <div class="stat-item">
                            <div class="stat-value">Rabu</div>
                            <div class="stat-label">Hari</div>
                        </div>
                    </div>
                </div>
                
                <div class="ekskul-item">
                    <div class="ekskul-info">
                        <div class="ekskul-icon">🕌</div>
                        <div class="ekskul-details">
                            <div class="ekskul-name">Rohani Islam</div>
                            <div class="ekskul-pembina">Pembina: Ust. Muhammad Ali</div>
                        </div>
                    </div>
                    <div class="ekskul-stats">
                        <div class="stat-item">
                            <div class="stat-value">28</div>
                            <div class="stat-label">Peserta</div>
                        </div>
                        <div class="divider"></div>
                        <div class="stat-item">
                            <div class="stat-value">Kamis</div>
                            <div class="stat-label">Hari</div>
                        </div>
                    </div>
                </div>
                
                <div class="ekskul-item">
                    <div class="ekskul-info">
                        <div class="ekskul-icon">🏀</div>
                        <div class="ekskul-details">
                            <div class="ekskul-name">Basket</div>
                            <div class="ekskul-pembina">Pembina: Bpk. Rudi Hartono</div>
                        </div>
                    </div>
                    <div class="ekskul-stats">
                        <div class="stat-item">
                            <div class="stat-value">25</div>
                            <div class="stat-label">Peserta</div>
                        </div>
                        <div class="divider"></div>
                        <div class="stat-item">
                            <div class="stat-value">Jumat</div>
                            <div class="stat-label">Hari</div>
                        </div>
                    </div>
                </div>
                
                <div class="ekskul-item">
                    <div class="ekskul-info">
                        <div class="ekskul-icon">🩺</div>
                        <div class="ekskul-details">
                            <div class="ekskul-name">PMR</div>
                            <div class="ekskul-pembina">Pembina: Ibu Dian Anggraini</div>
                        </div>
                    </div>
                    <div class="ekskul-stats">
                        <div class="stat-item">
                            <div class="stat-value">38</div>
                            <div class="stat-label">Peserta</div>
                        </div>
                        <div class="divider"></div>
                        <div class="stat-item">
                            <div class="stat-value">Sabtu</div>
                            <div class="stat-label">Hari</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <h3><span>ℹ️</span> Panduan Akses Sistem</h3>
            <ul>
                <li>Gunakan <strong>URL HTTPS</strong> untuk akses fitur presensi lokasi/GPS</li>
                <li><strong>Administrator:</strong> admin@sekolah.sch.id | password: admin123</li>
                <li><strong>Siswa:</strong> NIS sebagai username | password default: nis12345</li>
                <li><strong>Pembina:</strong> Email terdaftar | password default: pembina123</li>
                <li>Izinkan akses lokasi pada browser untuk presensi berbasis GPS</li>
                <li>Pastikan koneksi internet stabil saat melakukan presensi</li>
            </ul>
            
            <div class="footer-note">
                Sistem Presensi Ekstrakurikuler v1.0 • © 2024 Sekolah
            </div>
        </div>
    </div>

    <!-- Interactive Scripts -->
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
            
            // Add CSS for ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
            
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
            
            // Add counter animation for participant numbers
            const statValues = document.querySelectorAll('.stat-value');
            
            statValues.forEach(stat => {
                if (!isNaN(parseInt(stat.textContent))) {
                    const targetValue = parseInt(stat.textContent);
                    let currentValue = 0;
                    const increment = targetValue / 30;
                    const duration = 1500;
                    const interval = duration / 30;
                    
                    const timer = setInterval(() => {
                        currentValue += increment;
                        if (currentValue >= targetValue) {
                            currentValue = targetValue;
                            clearInterval(timer);
                        }
                        stat.textContent = Math.floor(currentValue);
                    }, interval);
                }
            });
        });
    </script>
</body>
</html>
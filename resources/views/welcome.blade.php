<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Absensi Ekskul</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: white;
        }
        .container {
            text-align: center;
            max-width: 800px;
            padding: 40px;
        }
        .logo {
            font-size: 48px;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 36px;
            margin-bottom: 30px;
            font-weight: 700;
        }
        .buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }
        .btn {
            padding: 16px 32px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            min-width: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-admin {
            background: #3b82f6;
            color: white;
        }
        .btn-siswa {
            background: #10b981;
            color: white;
        }
        .btn-pembina {
            background: #8b5cf6;
            color: white;
        }
        .btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .icon {
            font-size: 32px;
        }
        .role-name {
            font-size: 18px;
            font-weight: 600;
        }
        .role-desc {
            font-size: 14px;
            opacity: 0.9;
        }
        .info-box {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 20px;
            margin-top: 40px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">📚</div>
        <h1>Sistem Absensi Ekskul Sekolah</h1>
        <p>Selamat datang! Silakan pilih panel login sesuai peran Anda.</p>
        
        <div class="buttons">
            <a href="/admin/login" class="btn btn-admin">
                <div class="icon">👨‍💼</div>
                <div class="role-name">Admin</div>
                <div class="role-desc">Administrator Sistem</div>
            </a>
            
            <a href="/siswa/login" class="btn btn-siswa">
                <div class="icon">🎓</div>
                <div class="role-name">Siswa</div>
                <div class="role-desc">Absensi & Presensi</div>
            </a>
            
            <a href="/pembina/login" class="btn btn-pembina">
                <div class="icon">👨‍🏫</div>
                <div class="role-name">Pembina</div>
                <div class="role-desc">Pengelola Ekskul</div>
            </a>
        </div>
        
        <div class="info-box">
            <h3>⚠️ Testing dengan Ngrok:</h3>
            <ul>
                <li>• Gunakan URL ngrok (https://) untuk akses GPS</li>
                <li>• <strong>Admin:</strong> username: admin | password: password</li>
                <li>• <strong>Siswa:</strong> Login dengan akun siswa terdaftar</li>
                <li>• <strong>Pembina:</strong> Login dengan akun pembina</li>
            </ul>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Atur Ulang Kata Sandi - MARKAZ</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #0f172a;
            color: #ffffff;
            text-align: center;
            padding: 30px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.6;
        }
        .content h2 {
            margin-top: 0;
            color: #0f172a;
            font-size: 20px;
        }
        .otp-box {
            background-color: #f1f5f9;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            text-align: center;
            padding: 20px;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 6px;
            color: #10b981;
            margin: 0;
        }
        .btn {
            display: block;
            text-align: center;
            background-color: #10b981;
            color: #ffffff !important;
            text-decoration: none;
            font-weight: bold;
            padding: 15px 25px;
            border-radius: 12px;
            margin: 30px 0;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
        }
        .footer {
            background-color: #f8fafc;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>MARKAZ</h1>
        </div>
        <div class="content">
            <h2>Assalamu'alaikum Wr. Wb. Bpk/Ibu {{ $name }},</h2>
            <p>Kami menerima permintaan untuk mengatur ulang kata sandi akun <strong>MARKAZ (Aplikasi Informasi Masjid & I'tikaf)</strong> Anda.</p>
            
            <p>Silakan gunakan kode OTP di bawah ini untuk dimasukkan pada halaman verifikasi di aplikasi:</p>
            
            <div class="otp-box">
                <p class="otp-code">{{ $otp }}</p>
            </div>

            <p>Atau, Anda juga bisa langsung mengeklik tombol di bawah ini untuk langsung mengatur ulang kata sandi Anda:</p>
            
            <a href="{{ $url }}" class="btn">Atur Ulang Kata Sandi</a>

            <p style="font-size: 13px; color: #64748b; margin-top: 30px;">
                *Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini. Kode dan link ini akan kedaluwarsa dalam waktu 15 menit. Jangan bagikan kode ini kepada siapa pun.
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} MARKAZ. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

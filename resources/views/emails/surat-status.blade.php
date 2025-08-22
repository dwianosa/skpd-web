<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status Surat - SKDP Kominfo Bukittinggi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            margin: 20px 0;
        }
        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 SKDP Kominfo Bukittinggi</h1>
        <p>Update Status Pengajuan Surat</p>
    </div>
    
    <div class="content">
        <h2>Halo {{ $surat->nama_pemohon }}!</h2>
        
        <p>Status pengajuan surat Anda telah diperbarui:</p>
        
        <div style="text-align: center;">
            <div class="status-badge" style="background-color: {{ $statusColor }};">
                {{ $statusMessage }}
            </div>
        </div>
        
        <div class="info-box">
            <h3>📄 Detail Surat:</h3>
            <p><strong>Nomor Surat:</strong> {{ $surat->nomor_surat }}</p>
            <p><strong>Jenis Surat:</strong> {{ $surat->jenisSurat->nama_surat }}</p>
            <p><strong>Tanggal Pengajuan:</strong> {{ $surat->tanggal_pengajuan->format('d M Y') }}</p>
            @if($surat->tanggal_persetujuan)
            <p><strong>Tanggal Persetujuan:</strong> {{ $surat->tanggal_persetujuan->format('d M Y') }}</p>
            @endif
        </div>
        
        @if($surat->status == 'disetujui')
            <div class="info-box" style="border-left-color: #28a745;">
                <h3>🎉 Selamat! Surat Anda Disetujui</h3>
                <p>Surat persetujuan Anda telah selesai diproses dan <strong>DISETUJUI</strong>.</p>
                <p><strong>Langkah selanjutnya:</strong></p>
                <ul>
                    <li>Silakan ambil surat di kantor Kominfo Bukittinggi</li>
                    <li>Bawa kartu identitas (KTP) untuk verifikasi</li>
                    <li>Jam kerja: Senin - Jumat, 08:00 - 16:00 WIB</li>
                </ul>
            </div>
        @elseif($surat->status == 'ditolak')
            <div class="info-box" style="border-left-color: #dc3545;">
                <h3>⚠️ Surat Anda Ditolak</h3>
                <p>Mohon maaf, pengajuan surat Anda <strong>DITOLAK</strong>.</p>
                @if($surat->catatan)
                <p><strong>Catatan:</strong></p>
                <p style="background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
                    {{ $surat->catatan }}
                </p>
                @endif
                <p><strong>Langkah selanjutnya:</strong></p>
                <ul>
                    <li>Perbaiki dokumen sesuai catatan di atas</li>
                    <li>Ajukan ulang surat dengan dokumen yang sudah diperbaiki</li>
                    <li>Jika ada pertanyaan, hubungi kami di (0752) 21XXX</li>
                </ul>
            </div>
        @else
            <div class="info-box" style="border-left-color: #ffc107;">
                <h3>⏳ Surat Sedang Diproses</h3>
                <p>Surat Anda sedang dalam tahap <strong>VERIFIKASI</strong>.</p>
                <p><strong>Estimasi waktu proses:</strong></p>
                <ul>
                    <li>Verifikasi dokumen: 1-2 hari kerja</li>
                    <li>Review & persetujuan: 2-3 hari kerja</li>
                    <li>Penerbitan surat: 1 hari kerja</li>
                </ul>
                <p>Kami akan menghubungi Anda segera setelah proses selesai.</p>
            </div>
        @endif
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/tracking') }}" class="btn">🔍 Lacak Status Surat</a>
        </div>
        
        <div class="info-box">
            <h3>📞 Butuh Bantuan?</h3>
            <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan:</p>
            <ul>
                <li><strong>Telepon:</strong> (0752) 21XXX</li>
                <li><strong>Email:</strong> kominfo@bukittinggikota.go.id</li>
                <li><strong>Jam Kerja:</strong> Senin - Jumat, 08:00 - 16:00 WIB</li>
            </ul>
        </div>
    </div>
    
    <div class="footer">
        <p><strong>Dinas Komunikasi dan Informatika Kota Bukittinggi</strong></p>
        <p>Jl. Soekarno-Hatta No. 1, Bukittinggi, Sumatera Barat</p>
        <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
        <p>© {{ date('Y') }} SKDP Kominfo Bukittinggi. All rights reserved.</p>
    </div>
</body>
</html>

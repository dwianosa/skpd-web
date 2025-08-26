<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pengajuan Surat - SKPD Kominfo Bukittinggi</title>
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
            background-color: #ffc107;
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
        .highlight {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 SKPD Kominfo Bukittinggi</h1>
        <p>Konfirmasi Pengajuan Surat</p>
    </div>
    
    <div class="content">
        <h2>Halo {{ $surat->nama_pemohon }}!</h2>
        
        <p>Terima kasih telah mengajukan surat di SKPD Kominfo Bukittinggi. Berikut adalah konfirmasi pengajuan Anda:</p>
        
        <div style="text-align: center;">
            <div class="status-badge">
                📝 SURAT DITERIMA
            </div>
        </div>
        
        <div class="info-box">
            <h3>📄 Detail Pengajuan:</h3>
            <p><strong>Nomor Surat:</strong> {{ $surat->nomor_surat }}</p>
            <p><strong>Jenis Surat:</strong> {{ $surat->jenisSurat->nama_surat }}</p>
            <p><strong>Tanggal Pengajuan:</strong> {{ $surat->tanggal_pengajuan->format('d M Y H:i') }}</p>
            <p><strong>Status:</strong> <span style="color: #ffc107; font-weight: bold;">Sedang Diproses</span></p>
        </div>
        
        <div class="highlight">
            <h3>⏳ Status Saat Ini</h3>
            <p>Surat Anda telah <strong>BERHASIL DITERIMA</strong> dan sedang dalam tahap <strong>VERIFIKASI</strong>.</p>
            <p><strong>Estimasi waktu proses:</strong></p>
            <ul>
                <li>Verifikasi dokumen: 1-2 hari kerja</li>
                <li>Review & persetujuan: 2-3 hari kerja</li>
                <li>Penerbitan surat: 1 hari kerja</li>
            </ul>
        </div>
        
        <div class="info-box">
            <h3>🔍 Cara Tracking Surat</h3>
            <p>Anda dapat melacak status surat Anda dengan cara:</p>
            <ul>
                <li><strong>Website:</strong> Kunjungi website SKPD Kominfo Bukittinggi</li>
                <li><strong>Menu Tracking:</strong> Masukkan nomor surat: <strong>{{ $surat->nomor_surat }}</strong></li>
                <li><strong>Email Update:</strong> Kami akan mengirim update status via email</li>
            </ul>
        </div>
        
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
                <li><strong>Alamat:</strong> Jl. Soekarno-Hatta No. 1, Bukittinggi</li>
            </ul>
        </div>
        
        <div class="highlight">
            <h3>📧 Notifikasi Selanjutnya</h3>
            <p>Anda akan menerima email notifikasi setiap kali ada perubahan status surat:</p>
            <ul>
                <li>🟡 <strong>Sedang Diproses</strong> - Update progress verifikasi</li>
                <li>🟢 <strong>Disetujui</strong> - Instruksi pengambilan surat</li>
                <li>🔴 <strong>Ditolak</strong> - Alasan dan instruksi perbaikan</li>
            </ul>
        </div>
    </div>
    
    <div class="footer">
        <p><strong>Dinas Komunikasi dan Informatika Kota Bukittinggi</strong></p>
        <p>Jl. Soekarno-Hatta No. 1, Bukittinggi, Sumatera Barat</p>
        <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
        <p>© {{ date('Y') }} SKPD Kominfo Bukittinggi. All rights reserved.</p>
    </div>
</body>
</html>










@extends('layouts.app')

@section('title', 'Pengajuan Berhasil - SKPD Kominfo Bukittinggi')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Card -->
            <div class="card shadow-lg border-0">
                <div class="card-body text-center py-5">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    
                    <!-- Success Message -->
                    <h2 class="text-success fw-bold mb-3">Pengajuan Berhasil!</h2>
                    <p class="lead text-muted mb-4">
                        Terima kasih! Pengajuan surat SKPD Anda telah berhasil dikirim dan akan segera diproses.
                    </p>
                    
                    <!-- Nomor Surat -->
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-receipt"></i> Detail Pengajuan
                            </h5>
                            <div class="row">
                                <div class="col-sm-4 text-sm-end">
                                    <strong>Nomor Surat SKPD:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-primary fs-6 px-3 py-2">{{ $surat->nomor_surat }}</span>
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="row">
                                <div class="col-sm-4 text-sm-end">
                                    <strong>Jenis Surat SKPD:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $surat->jenisSurat->nama_surat }}
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="row">
                                <div class="col-sm-4 text-sm-end">
                                    <strong>Nama Pemohon:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $surat->nama_pemohon }}
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="row">
                                <div class="col-sm-4 text-sm-end">
                                    <strong>Tanggal Pengajuan:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $surat->tanggal_pengajuan->format('d M Y') }}
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="row">
                                <div class="col-sm-4 text-sm-end">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-warning">
                                        <i class="fas fa-hourglass-half"></i> Sedang Diproses
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Important Notice -->
                    <div class="alert alert-info text-start">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle"></i> Informasi Penting:
                        </h6>
                        <ul class="mb-0">
                            <li><strong>Simpan nomor surat</strong> untuk melacak status pengajuan Anda</li>
                            <li>Proses verifikasi membutuhkan waktu <strong>3-5 hari kerja</strong></li>
                            <li>Anda dapat melacak status melalui fitur "Lacak Surat" di website</li>
                            <li>Tim kami akan menghubungi Anda jika diperlukan informasi tambahan</li>
                        </ul>
                    </div>
                    
                    <!-- Next Steps -->
                    <div class="row mt-4">
                        <div class="col-md-4 mb-3">
                            <div class="card border-primary h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-search fa-2x text-primary mb-3"></i>
                                    <h6>Lacak Status</h6>
                                    <p class="small text-muted">Pantau perkembangan pengajuan surat Anda</p>
                                    <a href="{{ route('tracking') }}" class="btn btn-outline-primary btn-sm">
                                        Lacak Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card border-success h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-download fa-2x text-success mb-3"></i>
                                    <h6>Unduh Bukti</h6>
                                    <p class="small text-muted">Simpan bukti pengajuan untuk arsip Anda</p>
                                    <button onclick="window.print()" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-print"></i> Cetak
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card border-info h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-plus fa-2x text-info mb-3"></i>
                                    <h6>Ajukan Lagi</h6>
                                    <p class="small text-muted">Buat pengajuan surat persetujuan baru</p>
                                    <a href="{{ route('surat.create') }}" class="btn btn-outline-info btn-sm">
                                        Ajukan Surat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="text-muted mb-3">Butuh Bantuan?</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">
                                    <i class="fas fa-phone text-primary"></i> 
                                    (0752) 21XXX
                                </small>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">
                                    <i class="fas fa-envelope text-primary"></i> 
                                    kominfo@bukittinggikota.go.id
                                </small>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">
                                    <i class="fas fa-clock text-primary"></i> 
                                    Senin - Jumat, 08:00 - 16:00 WIB
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary me-md-2">
                            <i class="fas fa-home"></i> Kembali ke Beranda
                        </a>
                        <button onclick="copyToClipboard('{{ $surat->nomor_surat }}')" 
                                class="btn btn-primary" id="copyBtn">
                            <i class="fas fa-copy"></i> Salin Nomor Surat
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Update button text temporarily
        const btn = document.getElementById('copyBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-success');
        
        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-primary');
        }, 2000);
    });
}
</script>
@endpush
@endsection
@extends('layouts.app')

@section('title', 'SKPD Kominfo Bukittinggi - Beranda')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">
                    <img src="" alt="" style="vertical-align:middle; width:auto; height:auto; max-width:none; max-height:none;" class="me-3 logo-kominfo">
                    Sistem SKPD Kominfo Bukittinggi
                </h1>
                <p class="lead mb-4">
                    Layanan Persetujuan Surat SKPD untuk berbagai keperluan usaha 
                    bidang komunikasi dan informatika di Kota Bukittinggi
                </p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="{{ route('surat.create') }}" class="btn btn-light btn-lg me-md-2">
                        <i class="fas fa-plus"></i> Ajukan Surat Sekarang
                    </a>
                    <a href="{{ route('tracking') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-search"></i> Lacak Status Surat
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Info Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                        <h4>Proses Cepat</h4>
                        <p class="text-muted">Pengajuan surat diproses dalam waktu 3-5 hari kerja</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                        <h4>Online 24/7</h4>
                        <p class="text-muted">Ajukan kapan saja, di mana saja melalui sistem online</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h4>Aman & Terpercaya</h4>
                        <p class="text-muted">Data Anda aman dan sistem terpercaya</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Jenis Surat Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Jenis Surat SKPD</h2>
                <p class="text-muted">Pilih jenis surat SKPD yang sesuai dengan kebutuhan Anda</p>
            </div>
        </div>
        <div class="row">
            @forelse($jenisSurat as $jenis)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-file-alt fa-2x text-primary me-3 mt-1"></i>
                            <div>
                                <h5 class="card-title">{{ $jenis->nama_surat }}</h5>
                                <p class="card-text text-muted small">{{ $jenis->deskripsi }}</p>
                                
                                @if($jenis->persyaratan)
                                <div class="mt-3">
                                    <strong class="text-primary">Persyaratan:</strong>
                                    <ul class="list-unstyled mt-2">
                                        @foreach(explode(',', $jenis->persyaratan) as $syarat)
                                        <li class="small"><i class="fas fa-check text-success me-1"></i> {{ trim($syarat) }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">Belum ada jenis surat yang tersedia.</p>
            </div>
            @endforelse
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('surat.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-paper-plane"></i> Mulai Pengajuan
            </a>
        </div>
    </div>
</section>

<!-- Cara Pengajuan -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Cara Mengajukan Surat</h2>
                <p class="text-muted">Ikuti langkah-langkah mudah berikut</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 text-center mb-4">
                <div class="position-relative">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">1</span>
                    </div>
                </div>
                <h5 class="mt-3">Isi Formulir</h5>
                <p class="text-muted small">Lengkapi data diri dan keperluan surat</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="position-relative">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">2</span>
                    </div>
                </div>
                <h5 class="mt-3">Upload Dokumen</h5>
                <p class="text-muted small">Unggah file pendukung yang diperlukan</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="position-relative">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">3</span>
                    </div>
                </div>
                <h5 class="mt-3">Tunggu Verifikasi</h5>
                <p class="text-muted small">Tim kami akan memverifikasi dalam 3-5 hari kerja</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="position-relative">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">4</span>
                    </div>
                </div>
                <h5 class="mt-3">Surat Selesai</h5>
                <p class="text-muted small">Ambil surat di kantor atau unduh secara online</p>
            </div>
        </div>
    </div>
</section>

<!-- Kontak Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold mb-4">Butuh Bantuan?</h2>
                <p class="text-muted mb-4">Tim kami siap membantu Anda dalam proses pengajuan surat</p>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                                <h6>Telepon</h6>
                                <small class="text-muted">(0752) 21XXX</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                                <h6>Email</h6>
                                <small class="text-muted">kominfo@bukittinggikota.go.id</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-clock fa-2x text-primary mb-3"></i>
                                <h6>Jam Layanan</h6>
                                <small class="text-muted">Senin - Jumat<br>08:00 - 16:00 WIB</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
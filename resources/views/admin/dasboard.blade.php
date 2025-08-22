@extends('layouts.admin')

@section('title', 'Dashboard Admin - SKPD Kominfo Bukittinggi')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary mb-2">
                <i class="fas fa-tachometer-alt"></i> Dashboard Admin
            </h2>
            <p class="text-muted">Selamat datang di panel administrasi SKPD Kominfo Bukittinggi</p>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Surat
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalSurat">{{ $totalSurat ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-lg text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menunggu Proses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="suratPending">{{ $suratPending ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-lg text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Disetujui
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="suratDisetujui">{{ $suratDisetujui ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-lg text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Ditolak
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="suratDitolak">{{ $suratDitolak ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-lg text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart Statistik -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-area"></i> Statistik Pengajuan Surat SKPD (6 Bulan Terakhir)
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="chartSurat" width="100" height="40"></canvas>
                </div>
            </div>
        </div>

        <!-- Statistik Jenis Surat -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list"></i> Statistik per Jenis Surat SKPD
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($statistikJenis) && $statistikJenis->count() > 0)
                        @foreach($statistikJenis as $jenis)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-sm font-weight-bold">{{ Str::limit($jenis->nama_surat, 25) }}</span>
                                <span class="badge bg-primary">{{ $jenis->total_surat }}</span>
                            </div>
                            <div class="progress mt-1" style="height: 8px;">
                                <div class="progress-bar bg-primary" 
                                     style="width: {{ ($totalSurat ?? 0) > 0 ? ($jenis->total_surat / ($totalSurat ?? 1) * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">Belum ada data</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Surat Terbaru yang Perlu Diproses -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-check"></i> Surat SKPD Terbaru Perlu Diproses
                    </h6>
                    <a href="{{ route('admin.surat.index', ['status' => 'pending']) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($suratTerbaru) && $suratTerbaru->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nomor Surat</th>
                                        <th>Nama Pemohon</th>
                                        <th>Jenis Surat</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suratTerbaru as $surat)
                                    <tr>
                                        <td>
                                            <strong>{{ $surat->nomor_surat }}</strong>
                                        </td>
                                        <td>
                                            {{ $surat->nama_pemohon }}
                                            <br><small class="text-muted">{{ $surat->nik }}</small>
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($surat->jenisSurat->nama_surat, 30) }}</small>
                                        </td>
                                        <td>
                                            {{ $surat->tanggal_pengajuan->format('d M Y') }}
                                            <br><small class="text-muted">{{ $surat->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">
                                                <i class="fas fa-hourglass-half"></i> Pending
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.surat.show', $surat->id) }}" 
                                                   class="btn btn-outline-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.surat.edit', $surat->id) }}" 
                                                   class="btn btn-outline-primary" title="Edit Status">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak Ada Surat Pending</h5>
                            <p class="text-muted">Semua surat sudah diproses</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Info Bulan Ini -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Informasi:</strong> Bulan ini telah diterima <span id="suratBulanIni">{{ $suratBulanIni ?? 0 }}</span> pengajuan surat SKPD baru.
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row quick-actions">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.surat.index') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-file-alt"></i> Kelola Surat SKPD
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.jenis-surat.index') }}" class="btn btn-success btn-block">
                                <i class="fas fa-list"></i> Jenis Surat
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.demo-notifikasi') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-bell"></i> Demo Notifikasi
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.log-notifikasi') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-file-alt"></i> Log Notifikasi SKPD
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('tracking') }}" class="btn btn-info btn-block" target="_blank">
                                <i class="fas fa-search"></i> Test Tracking
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Download & Cetak Dokumen -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-download"></i> Download & Cetak Dokumen
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-search fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="card-title fw-bold">Lacak Status</h6>
                                    <p class="card-text text-muted small">Pantau perkembangan pengajuan surat Anda</p>
                                    <a href="{{ route('tracking') }}" class="btn btn-outline-primary btn-sm">
                                        Lacak Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-download fa-2x text-success"></i>
                                    </div>
                                    <h6 class="card-title fw-bold">Unduh Bukti</h6>
                                    <p class="card-text text-muted small">Simpan bukti pengajuan untuk arsip Anda</p>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-success" onclick="downloadDashboardDocx()">
                                            <i class="fas fa-file-word"></i> DOCX
                                        </button>
                                        <button type="button" class="btn btn-outline-info" onclick="downloadDashboardHtml()">
                                            <i class="fas fa-file-code"></i> HTML
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="printDashboard()">
                                            <i class="fas fa-print"></i> Cetak
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-plus fa-2x text-info"></i>
                                    </div>
                                    <h6 class="card-title fw-bold">Ajukan Lagi</h6>
                                    <p class="card-text text-muted small">Buat pengajuan surat persetujuan baru</p>
                                    <a href="{{ route('surat.create') }}" class="btn btn-outline-info btn-sm">
                                        Ajukan Surat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="{{ asset('css/admin-dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
<script>
$(document).ready(function() {
    // Chart Statistik Surat
    var ctx = document.getElementById('chartSurat').getContext('2d');
    
    // Data dari controller
    var chartData = @json($chartData ?? []);
    
    window.myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels || [],
            datasets: [{
                label: 'Jumlah Pengajuan',
                data: chartData.data || [],
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });
});
</script>
@endpush
@endsection

@extends('layouts.admin')

@section('title', 'Demo Email Notification - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary">
                        <i class="fas fa-envelope"></i> Demo Email Notification
                    </h2>
                    <p class="text-muted">Test dan monitoring sistem notifikasi email</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>

            <!-- Email Configuration Status -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-cog"></i> Konfigurasi Email
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Mail Driver:</strong> 
                                <span class="badge bg-primary">{{ config('mail.default') }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>From Address:</strong> 
                                <span>{{ config('mail.from.address', 'Not configured') }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>From Name:</strong> 
                                <span>{{ config('mail.from.name', 'Not configured') }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Status:</strong> 
                                @if(config('mail.default') == 'log')
                                    <span class="badge bg-warning">Development Mode (Log)</span>
                                @else
                                    <span class="badge bg-success">Production Mode (SMTP)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar"></i> Statistik Email
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <h4 class="text-primary">{{ $totalSurat }}</h4>
                                    <small>Total Surat</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-warning">{{ $pendingSurat }}</h4>
                                    <small>Pending</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-success">{{ $processedSurat }}</h4>
                                    <small>Diproses</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Email Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-flask"></i> Test Email Notification
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Test Basic Email</h6>
                                    <p class="text-muted">Test pengiriman email sederhana</p>
                                    <button class="btn btn-outline-primary" onclick="testBasicEmail()">
                                        <i class="fas fa-paper-plane"></i> Test Basic Email
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <h6>Test Email Template</h6>
                                    <p class="text-muted">Test email dengan template lengkap</p>
                                    <button class="btn btn-outline-success" onclick="testEmailTemplate()">
                                        <i class="fas fa-envelope-open"></i> Test Email Template
                                    </button>
                                </div>
                            </div>
                            
                            <div id="test-result" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Logs -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-list"></i> Email Logs
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-secondary" onclick="refreshLogs()">
                                    <i class="fas fa-sync"></i> Refresh Logs
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="clearLogs()">
                                    <i class="fas fa-trash"></i> Clear Logs
                                </button>
                            </div>
                            
                            <div id="email-logs" class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;">
                                <p class="text-muted">Klik "Refresh Logs" untuk melihat log email...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Templates Preview -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-eye"></i> Preview Email Templates
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card border-success">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0">Email Disetujui</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="small">Email yang dikirim saat surat disetujui</p>
                                            <ul class="small">
                                                <li>Status hijau "DISETUJUI"</li>
                                                <li>Instruksi pengambilan</li>
                                                <li>Informasi jam kerja</li>
                                                <li>Link tracking</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-danger">
                                        <div class="card-header bg-danger text-white">
                                            <h6 class="mb-0">Email Ditolak</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="small">Email yang dikirim saat surat ditolak</p>
                                            <ul class="small">
                                                <li>Status merah "DITOLAK"</li>
                                                <li>Catatan admin</li>
                                                <li>Instruksi perbaikan</li>
                                                <li>Kontak bantuan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-warning">
                                        <div class="card-header bg-warning text-dark">
                                            <h6 class="mb-0">Email Pending</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="small">Email yang dikirim saat status pending</p>
                                            <ul class="small">
                                                <li>Status kuning "DIPROSES"</li>
                                                <li>Estimasi waktu</li>
                                                <li>Tahapan verifikasi</li>
                                                <li>Update berkala</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function testBasicEmail() {
    $('#test-result').html('<div class="alert alert-info">Testing basic email...</div>');
    
    $.get('/test-email')
        .done(function(response) {
            if (response.status === 'success') {
                $('#test-result').html(`
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> ${response.message}
                        <br><small>Log: ${response.log_location}</small>
                    </div>
                `);
            } else {
                $('#test-result').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> ${response.message}
                    </div>
                `);
            }
        })
        .fail(function() {
            $('#test-result').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Gagal melakukan test email
                </div>
            `);
        });
}

function testEmailTemplate() {
    $('#test-result').html('<div class="alert alert-info">Testing email template...</div>');
    
    $.get('/test-email-template')
        .done(function(response) {
            if (response.status === 'success') {
                $('#test-result').html(`
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> ${response.message}
                        <br><small>Surat ID: ${response.surat_id}, Status: ${response.status}</small>
                        <br><small>Log: ${response.log_location}</small>
                    </div>
                `);
            } else {
                $('#test-result').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> ${response.message}
                    </div>
                `);
            }
        })
        .fail(function() {
            $('#test-result').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Gagal melakukan test email template
                </div>
            `);
        });
}

function refreshLogs() {
    $('#email-logs').html('<p class="text-muted">Loading logs...</p>');
    
    $.get('/admin/log-notifikasi')
        .done(function(response) {
            $('#email-logs').html(response);
        })
        .fail(function() {
            $('#email-logs').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Gagal memuat logs
                </div>
            `);
        });
}

function clearLogs() {
    if (confirm('Apakah Anda yakin ingin menghapus semua log email?')) {
        $.post('/admin/log-notifikasi/clear')
            .done(function(response) {
                $('#email-logs').html(`
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Logs berhasil dihapus
                    </div>
                `);
            })
            .fail(function() {
                $('#email-logs').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> Gagal menghapus logs
                    </div>
                `);
            });
    }
}

// Auto refresh logs setiap 30 detik
setInterval(refreshLogs, 30000);
</script>
@endpush
@endsection








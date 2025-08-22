@extends('layouts.app')

@section('title', 'Lacak Status Surat - SKPD Kominfo Bukittinggi')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-search"></i> Lacak Status Surat SKPD
                </h2>
                <p class="text-muted">Masukkan nomor surat SKPD untuk mengetahui status pengajuan Anda</p>
            </div>

            <!-- Form Tracking -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form id="trackingForm">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="nomor_surat" class="form-label fw-bold">Nomor Surat SKPD</label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="nomor_surat" 
                                       name="nomor_surat" 
                                       placeholder="Contoh: SKPD/001/08/2025/KOMINFO-BTG"
                                       required>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Nomor surat dapat ditemukan pada email konfirmasi atau bukti pengajuan
                                </small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-search"></i> Lacak Sekarang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Loading -->
            <div id="loading" class="text-center" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Mencari data surat...</p>
            </div>

            <!-- Hasil Tracking -->
            <div id="hasilTracking" style="display: none;">
                <!-- Content akan diisi via JavaScript -->
            </div>

            <!-- Informasi Tambahan -->
            <div class="row mt-5">
                <div class="col-md-6 mb-4">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-clock"></i> Estimasi Waktu Proses
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <strong>Verifikasi Dokumen:</strong> 1-2 hari kerja
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <strong>Review & Persetujuan:</strong> 2-3 hari kerja
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <strong>Penerbitan Surat:</strong> 1 hari kerja
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-question-circle"></i> Butuh Bantuan?
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">Jika Anda mengalami kesulitan atau memiliki pertanyaan:</p>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-1">
                                    <i class="fas fa-phone text-primary"></i>
                                    Telepon: (0752) 21XXX
                                </li>
                                <li class="mb-1">
                                    <i class="fas fa-envelope text-primary"></i>
                                    Email: kominfo@bukittinggikota.go.id
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-clock text-primary"></i>
                                    Senin - Jumat: 08:00 - 16:00 WIB
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#trackingForm').submit(function(e) {
        e.preventDefault();
        
        var nomorSurat = $('#nomor_surat').val().trim();
        
        if (!nomorSurat) {
            alert('Silakan masukkan nomor surat!');
            return;
        }
        
        // Show loading
        $('#loading').show();
        $('#hasilTracking').hide();
        
        // AJAX request - using POST method
        $.ajax({
            url: '/track',
            type: 'POST',
            data: {
                nomor_surat: nomorSurat,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loading').hide();
                
                if (response.status === 'found') {
                    showHasilTracking(response.data);
                } else if (response.status === 'not_found') {
                    showNotFound();
                } else {
                    showError(response.message || 'Terjadi kesalahan');
                }
            },
            error: function(xhr) {
                $('#loading').hide();
                console.log('Error:', xhr);
                showError('Terjadi kesalahan pada server');
            }
        });
    });
    
    function showHasilTracking(data) {
        var statusBadge = '';
        var statusIcon = '';
        var statusText = '';
        
        switch(data.status) {
            case 'pending':
                statusBadge = 'bg-warning';
                statusIcon = 'fas fa-hourglass-half';
                statusText = 'Sedang Diproses';
                break;
            case 'disetujui':
                statusBadge = 'bg-success';
                statusIcon = 'fas fa-check-circle';
                statusText = 'Disetujui';
                break;
            case 'ditolak':
                statusBadge = 'bg-danger';
                statusIcon = 'fas fa-times-circle';
                statusText = 'Ditolak';
                break;
        }
        
        var tanggalPengajuan = new Date(data.tanggal_pengajuan).toLocaleDateString('id-ID');
        var tanggalPersetujuan = data.tanggal_persetujuan ? 
            new Date(data.tanggal_persetujuan).toLocaleDateString('id-ID') : '-';
        
        var html = `
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt"></i> 
                        Detail Status Surat
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Status Badge -->
                    <div class="text-center mb-4">
                        <span class="badge ${statusBadge} fs-6 px-4 py-3">
                            <i class="${statusIcon}"></i> ${statusText}
                        </span>
                    </div>
                    
                    <!-- Detail Informasi -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Nomor Surat:</td>
                                    <td>${data.nomor_surat}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Jenis Surat:</td>
                                    <td>${data.jenis_surat.nama_surat}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nama Pemohon:</td>
                                    <td>${data.nama_pemohon}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tanggal Pengajuan:</td>
                                    <td>${tanggalPengajuan}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Status:</td>
                                    <td><span class="badge ${statusBadge}">${statusText}</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tanggal ${data.status === 'disetujui' ? 'Persetujuan' : 'Update'}:</td>
                                    <td>${tanggalPersetujuan}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">NIK:</td>
                                    <td>${data.nik}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">No. Telepon:</td>
                                    <td>${data.no_telp}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Keperluan -->
                    <div class="mt-3">
                        <strong>Keperluan:</strong>
                        <p class="mt-2 p-3 bg-light rounded">${data.keperluan}</p>
                    </div>
                    
                    <!-- Catatan -->
                    ${data.catatan ? `
                        <div class="mt-3">
                            <strong>Catatan:</strong>
                            <p class="mt-2 p-3 bg-light rounded">${data.catatan}</p>
                        </div>
                    ` : ''}
                    
                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <strong>Progress Pengajuan:</strong>
                        <div class="mt-2">
                            ${getProgressBar(data.status)}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#hasilTracking').html(html).fadeIn();
    }
    
    function getProgressBar(status) {
        var progress = 25;
        var steps = [
            {name: 'Pengajuan', active: true},
            {name: 'Verifikasi', active: false},
            {name: 'Review', active: false},
            {name: 'Selesai', active: false}
        ];
        
        switch(status) {
            case 'pending':
                progress = 50;
                steps[1].active = true;
                break;
            case 'disetujui':
                progress = 100;
                steps[1].active = true;
                steps[2].active = true;
                steps[3].active = true;
                break;
            case 'ditolak':
                progress = 75;
                steps[1].active = true;
                steps[2].active = true;
                break;
        }
        
        var stepsHtml = steps.map(step => `
            <div class="text-center">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center ${step.active ? 'bg-primary text-white' : 'bg-light'}" style="width: 40px; height: 40px;">
                    <i class="fas fa-check"></i>
                </div>
                <p class="small mt-2 mb-0">${step.name}</p>
            </div>
        `).join('');
        
        return `
            <div class="progress mb-3" style="height: 10px;">
                <div class="progress-bar bg-primary" style="width: ${progress}%"></div>
            </div>
            <div class="d-flex justify-content-between">
                ${stepsHtml}
            </div>
        `;
    }
    
    function showNotFound() {
        var html = `
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Surat Tidak Ditemukan</h4>
                    <p class="text-muted mb-4">
                        Nomor surat yang Anda masukkan tidak ditemukan dalam sistem. 
                        Pastikan nomor surat yang dimasukkan sudah benar.
                    </p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Tips:</strong> 
                        Periksa kembali nomor surat pada email konfirmasi atau bukti pengajuan Anda.
                    </div>
                    <button onclick="$('#nomor_surat').focus()" class="btn btn-primary">
                        <i class="fas fa-redo"></i> Coba Lagi
                    </button>
                </div>
            </div>
        `;
        $('#hasilTracking').html(html).fadeIn();
    }
    
    function showError(message) {
        var html = `
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h4 class="text-warning">Terjadi Kesalahan</h4>
                    <p class="text-muted mb-4">${message}</p>
                    <button onclick="$('#trackingForm').submit()" class="btn btn-warning">
                        <i class="fas fa-redo"></i> Coba Lagi
                    </button>
                </div>
            </div>
        `;
        $('#hasilTracking').html(html).fadeIn();
    }
});
</script>
@endpush
@endsection
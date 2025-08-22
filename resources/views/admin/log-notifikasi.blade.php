@extends('layouts.app')

@section('title', 'Log Notifikasi Persetujuan Surat SKPD - Admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                                            <h4 class="mb-0">
                        <i class="fas fa-file-alt"></i> Log Notifikasi Persetujuan Surat SKPD
                    </h4>
                        <div>
                            <a href="{{ route('admin.demo-notifikasi') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-bell"></i> Demo Notifikasi
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Dashboard
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Info:</strong> Halaman ini menampilkan log notifikasi persetujuan surat SKPD yang dikirim sistem. Log disimpan di file <code>storage/logs/laravel.log</code>
                    </div>

                    <!-- Filter dan Search -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="searchLog" placeholder="Cari log notifikasi...">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-success" onclick="refreshLog()">
                                <i class="fas fa-sync-alt"></i> Refresh Log
                            </button>
                            <button class="btn btn-warning" onclick="clearLog()">
                                <i class="fas fa-trash"></i> Clear Log
                            </button>
                        </div>
                    </div>

                    <!-- Log Notifikasi -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="logTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Timestamp</th>
                                    <th>Level</th>
                                    <th>Nama Pemohon</th>
                                    <th>Pesan Notifikasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="logTableBody">
                                <!-- Log akan diisi via JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Log pagination">
                            <ul class="pagination" id="logPagination">
                                <!-- Pagination akan diisi via JavaScript -->
                            </ul>
                        </nav>
                    </div>

                    <!-- Statistik Log -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 id="totalLogs">0</h5>
                                    <small>Total Log</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 id="successLogs">0</h5>
                                    <small>Berhasil</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 id="pendingLogs">0</h5>
                                    <small>Pending</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5 id="rejectedLogs">0</h5>
                                    <small>Ditolak</small>
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
let currentPage = 1;
const logsPerPage = 10;
let allLogs = [];

$(document).ready(function() {
    loadLogs();
    
    // Search functionality
    $('#searchLog').on('input', function() {
        filterLogs();
    });
});

function loadLogs() {
    $.ajax({
        url: '{{ route("admin.log-notifikasi.api") }}',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                allLogs = response.logs;
                displayLogs();
                updateStats();
            } else {
                showAlert('error', 'Gagal memuat log: ' + response.message);
            }
        },
        error: function() {
            showAlert('error', 'Gagal memuat log notifikasi');
        }
    });
}

function displayLogs() {
    const startIndex = (currentPage - 1) * logsPerPage;
    const endIndex = startIndex + logsPerPage;
    const pageLogs = allLogs.slice(startIndex, endIndex);
    
    let html = '';
    pageLogs.forEach((log, index) => {
        const rowNumber = startIndex + index + 1;
        const status = getStatusFromMessage(log.message);
        const statusClass = getStatusClass(status);
        
        html += `
            <tr>
                <td>${rowNumber}</td>
                <td>${formatTimestamp(log.timestamp)}</td>
                <td><span class="badge bg-info">${log.level}</span></td>
                <td><strong>${log.nama_pemohon}</strong></td>
                <td>${log.message}</td>
                <td><span class="badge ${statusClass}">${status}</span></td>
            </tr>
        `;
    });
    
    $('#logTableBody').html(html);
    updatePagination();
}

function getStatusFromMessage(message) {
    if (message.includes('DISETUJUI')) return 'Disetujui';
    if (message.includes('DITOLAK')) return 'Ditolak';
    if (message.includes('verifikasi') || message.includes('proses')) return 'Pending';
    return 'Info';
}

function getStatusClass(status) {
    switch(status) {
        case 'Disetujui': return 'bg-success';
        case 'Ditolak': return 'bg-danger';
        case 'Pending': return 'bg-warning';
        default: return 'bg-info';
    }
}

function formatTimestamp(timestamp) {
    const date = new Date(timestamp);
    return date.toLocaleString('id-ID');
}

function updatePagination() {
    const totalPages = Math.ceil(allLogs.length / logsPerPage);
    let html = '';
    
    // Previous button
    html += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">Previous</a>
        </li>
    `;
    
    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        html += `
            <li class="page-item ${currentPage === i ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
            </li>
        `;
    }
    
    // Next button
    html += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">Next</a>
        </li>
    `;
    
    $('#logPagination').html(html);
}

function changePage(page) {
    if (page < 1 || page > Math.ceil(allLogs.length / logsPerPage)) return;
    currentPage = page;
    displayLogs();
}

function filterLogs() {
    const searchTerm = $('#searchLog').val().toLowerCase();
    const filteredLogs = allLogs.filter(log => 
        log.nama_pemohon.toLowerCase().includes(searchTerm) ||
        log.message.toLowerCase().includes(searchTerm)
    );
    
    allLogs = filteredLogs;
    currentPage = 1;
    displayLogs();
    updateStats();
}

function updateStats() {
    $('#totalLogs').text(allLogs.length);
    $('#successLogs').text(allLogs.filter(log => getStatusFromMessage(log.message) === 'Disetujui').length);
    $('#pendingLogs').text(allLogs.filter(log => getStatusFromMessage(log.message) === 'Pending').length);
    $('#rejectedLogs').text(allLogs.filter(log => getStatusFromMessage(log.message) === 'Ditolak').length);
}

function refreshLog() {
    loadLogs();
    showAlert('success', 'Log berhasil di-refresh!');
}

function clearLog() {
    if (confirm('Apakah Anda yakin ingin menghapus semua log? Tindakan ini tidak dapat dibatalkan!')) {
        $.ajax({
            url: '{{ route("admin.log-notifikasi.clear") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    allLogs = [];
                    displayLogs();
                    updateStats();
                    showAlert('success', 'Log berhasil dihapus!');
                } else {
                    showAlert('error', 'Gagal menghapus log: ' + response.message);
                }
            },
            error: function() {
                showAlert('error', 'Gagal menghapus log');
            }
        });
    }
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const html = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('.card-body').prepend(html);
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 3000);
}
</script>
@endpush
@endsection

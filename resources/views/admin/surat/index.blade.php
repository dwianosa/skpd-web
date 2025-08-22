@extends('layouts.admin')

@section('title', 'Kelola Surat - Admin')

@push('styles')
<style>
/* Prevent duplicate content */
.container {
    position: relative;
    z-index: 1;
}

/* Ensure single table display */
.table-responsive {
    overflow-x: auto;
    overflow-y: hidden;
}

/* Remove any duplicate elements */
.container > .row:not(:first-child) {
    display: none;
}

/* Force single layout */
body {
    overflow-x: hidden;
}

/* Clear any floating elements */
.clearfix::after {
    content: "";
    clear: both;
    display: table;
}
</style>
@endpush

@section('content')
<!-- Prevent caching -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<div class="container py-5 clearfix">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary">
                        <i class="fas fa-file-alt"></i> Kelola Surat Persetujuan
                    </h2>
                    <p class="text-muted">Kelola semua pengajuan surat persetujuan</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $surat->total() }}</h4>
                                    <small>Total Surat</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-file-alt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $surat->where('status', 'pending')->count() }}</h4>
                                    <small>Sedang Diproses</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $surat->where('status', 'disetujui')->count() }}</h4>
                                    <small>Disetujui</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $surat->where('status', 'ditolak')->count() }}</h4>
                                    <small>Ditolak</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-times-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Surat Table -->
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Daftar Surat Persetujuan
                    </h5>
                </div>
                <div class="card-body">
                    @if($surat->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Surat</th>
                                        <th>Pemohon</th>
                                        <th>Jenis Surat</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($surat as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->nomor_surat }}</strong>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $item->nama_pemohon }}</strong><br>
                                                <small class="text-muted">{{ $item->nik }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $item->jenisSurat->nama_surat }}</td>
                                        <td>{{ $item->tanggal_pengajuan->format('d M Y') }}</td>
                                        <td>
                                            @if($item->status == 'pending')
                                                <span class="badge bg-warning">Sedang Diproses</span>
                                            @elseif($item->status == 'disetujui')
                                                <span class="badge bg-success">Disetujui</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.surat.show', $item->id) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.surat.edit', $item->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Edit Status">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($item->status === 'disetujui')
                                                <a href="{{ route('admin.surat.export-docx', $item->id) }}"
                                                   class="btn btn-sm btn-outline-success"
                                                   title="Download DOCX">
                                                    <i class="fas fa-file-word"></i>
                                                </a>
                                                @endif
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteSurat({{ $item->id }})"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $surat->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada surat persetujuan</h5>
                            <p class="text-muted">Surat yang diajukan akan muncul di sini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus surat ini?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Prevent page caching
if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
    window.location.reload();
}

// Remove any duplicate elements on page load
$(document).ready(function() {
    // Remove duplicate containers
    $('.container').not(':first').remove();
    
    // Ensure single table
    $('.table-responsive').not(':first').remove();
    
    // Force single layout
    $('body').css('overflow-x', 'hidden');
});

function deleteSurat(id) {
    if (confirm('Apakah Anda yakin ingin menghapus surat ini?')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/surat/' + id;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection

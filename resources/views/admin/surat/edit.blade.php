@extends('layouts.app')

@section('title', 'Edit Status Surat - Admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i> Update Status Surat
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Detail Surat -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Nomor Surat:</td>
                                    <td>{{ $surat->nomor_surat }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Jenis Surat:</td>
                                    <td>{{ $surat->jenisSurat->nama_surat }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nama Pemohon:</td>
                                    <td>{{ $surat->nama_pemohon }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">NIK:</td>
                                    <td>{{ $surat->nik }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Status Saat Ini:</td>
                                    <td>
                                        @if($surat->status == 'pending')
                                            <span class="badge bg-warning">Sedang Diproses</span>
                                        @elseif($surat->status == 'disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tanggal Pengajuan:</td>
                                    <td>{{ $surat->tanggal_pengajuan->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">No. Telepon:</td>
                                    <td>{{ $surat->no_telp }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Email:</td>
                                    <td>{{ $surat->email ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Keperluan -->
                    <div class="mb-4">
                        <strong>Keperluan:</strong>
                        <p class="mt-2 p-3 bg-light rounded">{{ $surat->keperluan }}</p>
                    </div>

                    <!-- Form Update Status -->
                    <form action="{{ route('admin.surat.update', $surat->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold">
                                    <i class="fas fa-tasks"></i> Update Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="pending" {{ $surat->status == 'pending' ? 'selected' : '' }}>
                                        Sedang Diproses
                                    </option>
                                    <option value="disetujui" {{ $surat->status == 'disetujui' ? 'selected' : '' }}>
                                        Disetujui
                                    </option>
                                    <option value="ditolak" {{ $surat->status == 'ditolak' ? 'selected' : '' }}>
                                        Ditolak
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tanggal_persetujuan" class="form-label fw-bold">
                                    <i class="fas fa-calendar"></i> Tanggal Persetujuan
                                </label>
                                <input type="date" 
                                       class="form-control @error('tanggal_persetujuan') is-invalid @enderror" 
                                       id="tanggal_persetujuan" name="tanggal_persetujuan"
                                       value="{{ $surat->tanggal_persetujuan ? $surat->tanggal_persetujuan->format('Y-m-d') : '' }}">
                                @error('tanggal_persetujuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Kosongkan jika belum disetujui</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="catatan" class="form-label fw-bold">
                                <i class="fas fa-comment"></i> Catatan/Keterangan
                            </label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="catatan" name="catatan" rows="4" 
                                      placeholder="Berikan catatan atau keterangan untuk pemohon...">{{ $surat->catatan }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Catatan ini akan ditampilkan kepada pemohon saat tracking surat
                            </small>
                        </div>

                        <!-- Preview Notifikasi -->
                        <div class="mb-4">
                            <h6 class="fw-bold">
                                <i class="fas fa-bell"></i> Preview Notifikasi
                            </h6>
                            <div id="preview-notifikasi" class="p-3 bg-light rounded">
                                <p class="mb-0" id="notifikasi-text">
                                    Pilih status untuk melihat preview notifikasi...
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.surat.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Update preview notifikasi saat status berubah
    $('#status').change(function() {
        updateNotificationPreview();
    });
    
    function updateNotificationPreview() {
        var status = $('#status').val();
        var nomorSurat = '{{ $surat->nomor_surat }}';
        var message = '';
        
        switch(status) {
            case 'disetujui':
                message = `Selamat! Surat Anda dengan nomor ${nomorSurat} telah DISETUJUI. Silakan ambil surat di kantor Kominfo Bukittinggi.`;
                break;
            case 'ditolak':
                message = `Mohon maaf, surat Anda dengan nomor ${nomorSurat} DITOLAK. Silakan perbaiki sesuai catatan yang diberikan.`;
                break;
            case 'pending':
                message = `Surat Anda dengan nomor ${nomorSurat} sedang dalam proses verifikasi. Kami akan menghubungi Anda segera.`;
                break;
        }
        
        $('#notifikasi-text').text(message);
    }
    
    // Auto-set tanggal persetujuan saat status disetujui
    $('#status').change(function() {
        if ($(this).val() === 'disetujui') {
            var today = new Date().toISOString().split('T')[0];
            $('#tanggal_persetujuan').val(today);
        }
    });
});
</script>
@endpush
@endsection

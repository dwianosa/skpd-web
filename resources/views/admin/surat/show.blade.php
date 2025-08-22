@extends('layouts.admin')

@section('title', 'Detail Surat - Admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-file-alt"></i> Detail Surat Persetujuan
                        </h4>
                        <div>
                            <a href="{{ route('admin.surat.edit', $surat->id) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-edit"></i> Edit Status
                            </a>
                            <a href="{{ route('admin.surat.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Status Badge -->
                    <div class="text-center mb-4">
                        @if($surat->status == 'pending')
                            <span class="badge bg-warning fs-6 px-4 py-3">
                                <i class="fas fa-hourglass-half"></i> Sedang Diproses
                            </span>
                        @elseif($surat->status == 'disetujui')
                            <span class="badge bg-success fs-6 px-4 py-3">
                                <i class="fas fa-check-circle"></i> Disetujui
                            </span>
                        @else
                            <span class="badge bg-danger fs-6 px-4 py-3">
                                <i class="fas fa-times-circle"></i> Ditolak
                            </span>
                        @endif
                    </div>

                    <!-- Detail Informasi -->
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-info-circle"></i> Informasi Surat
                            </h6>
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
                                    <td class="fw-bold">Tanggal Pengajuan:</td>
                                    <td>{{ $surat->tanggal_pengajuan->format('d M Y') }}</td>
                                </tr>
                                @if($surat->tanggal_persetujuan)
                                <tr>
                                    <td class="fw-bold">Tanggal Persetujuan:</td>
                                    <td>{{ $surat->tanggal_persetujuan->format('d M Y') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-user"></i> Data Pemohon
                            </h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Nama Lengkap:</td>
                                    <td>{{ $surat->nama_pemohon }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">NIK:</td>
                                    <td>{{ $surat->nik }}</td>
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

                    <!-- Alamat -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-2">
                            <i class="fas fa-map-marker-alt"></i> Alamat
                        </h6>
                        <p class="p-3 bg-light rounded">{{ $surat->alamat }}</p>
                    </div>

                    <!-- Keperluan -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-2">
                            <i class="fas fa-clipboard"></i> Keperluan
                        </h6>
                        <p class="p-3 bg-light rounded">{{ $surat->keperluan }}</p>
                    </div>

                    <!-- Catatan Admin -->
                    @if($surat->catatan)
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-2">
                            <i class="fas fa-comment"></i> Catatan Admin
                        </h6>
                        <div class="p-3 bg-warning bg-opacity-10 border border-warning rounded">
                            {{ $surat->catatan }}
                        </div>
                    </div>
                    @endif

                    <!-- File Pendukung -->
                    @if($surat->file_pendukung)
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-2">
                            <i class="fas fa-paperclip"></i> File Pendukung
                        </h6>
                        <div class="p-3 bg-light rounded">
                            <i class="fas fa-file"></i>
                            <a href="{{ asset('uploads/' . $surat->file_pendukung) }}" 
                               target="_blank" class="text-decoration-none">
                                {{ $surat->file_pendukung }}
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Timeline -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-history"></i> Timeline Proses
                        </h6>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Surat Diajukan</h6>
                                    <p class="text-muted mb-0">{{ $surat->tanggal_pengajuan->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            
                            @if($surat->status != 'pending')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $surat->status == 'disetujui' ? 'success' : 'danger' }}"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">
                                        Surat {{ $surat->status == 'disetujui' ? 'Disetujui' : 'Ditolak' }}
                                    </h6>
                                    <p class="text-muted mb-0">
                                        {{ $surat->tanggal_persetujuan ? $surat->tanggal_persetujuan->format('d M Y H:i') : 'Baru saja' }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.surat.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                        </a>
                        <div>
                            @if($surat->status === 'disetujui')
                            <a href="{{ route('admin.surat.export-docx', $surat->id) }}" class="btn btn-success">
                                <i class="fas fa-file-word"></i> Download DOCX
                            </a>
                            @endif
                            <a href="{{ route('admin.surat.edit', $surat->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Update Status
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}
</style>
@endsection

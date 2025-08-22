@extends('layouts.app')

@section('title', 'Demo Notifikasi Persetujuan Surat SKPD - Admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-bell"></i> Demo Sistem Notifikasi Persetujuan Surat SKPD
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Info:</strong> Halaman ini untuk testing sistem notifikasi persetujuan surat SKPD. Pilih surat dan update status untuk melihat notifikasi yang dikirim.
                    </div>

                    <!-- Daftar Surat untuk Test -->
                    <h5 class="mb-3">
                        <i class="fas fa-list"></i> Surat untuk Testing
                    </h5>
                    
                    @php
                        $testSurat = \App\Models\SuratPersetujuan::with('jenisSurat')->limit(5)->get();
                    @endphp
                    
                    @if($testSurat->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Surat</th>
                                        <th>Pemohon</th>
                                        <th>Status</th>
                                        <th>Email</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($testSurat as $index => $surat)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $surat->nomor_surat }}</strong>
                                        </td>
                                        <td>{{ $surat->nama_pemohon }}</td>
                                        <td>
                                            @if($surat->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($surat->status == 'disetujui')
                                                <span class="badge bg-success">Disetujui</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($surat->email)
                                                <span class="text-success">
                                                    <i class="fas fa-check"></i> {{ $surat->email }}
                                                </span>
                                            @else
                                                <span class="text-danger">
                                                    <i class="fas fa-times"></i> Tidak ada email
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.surat.edit', $surat->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Update Status
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada surat untuk testing</h5>
                            <p class="text-muted">Buat surat terlebih dahulu untuk test notifikasi</p>
                        </div>
                    @endif

                    <!-- Preview Notifikasi -->
                    <div class="mt-5">
                        <h5 class="mb-3">
                            <i class="fas fa-eye"></i> Preview Notifikasi
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-white">
                                        <h6 class="mb-0">Pending</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="small">"Surat Anda sedang dalam proses verifikasi. Kami akan menghubungi Anda segera."</p>
                                        <small class="text-muted">Email + Log</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">Disetujui</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="small">"Selamat! Surat Anda telah DISETUJUI. Silakan ambil di kantor Kominfo Bukittinggi."</p>
                                        <small class="text-muted">Email + Log</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card border-danger">
                                    <div class="card-header bg-danger text-white">
                                        <h6 class="mb-0">Ditolak</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="small">"Mohon maaf, surat Anda DITOLAK. Silakan perbaiki sesuai catatan."</p>
                                        <small class="text-muted">Email + Log</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Log Notifikasi -->
                    <div class="mt-4">
                        <h5 class="mb-3">
                            <i class="fas fa-file-alt"></i> Log Notifikasi
                        </h5>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-2"><strong>Lokasi log:</strong> <code>storage/logs/laravel.log</code></p>
                            <p class="mb-0"><strong>Format log:</strong> <code>Notifikasi untuk [nama]: [pesan]</code></p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4">
                        <a href="{{ route('admin.surat.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Kelola Surat SKPD
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

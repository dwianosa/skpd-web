@extends('layouts.admin')

@section('title', 'Jenis Surat - Admin SKPD')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card admin-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Daftar Jenis Surat
                </h5>
                <a href="{{ route('admin.jenis-surat.create') }}" class="btn btn-admin-success">
                    <i class="fas fa-plus me-2"></i>Tambah Jenis Surat
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-admin">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-admin">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table admin-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="40%">Nama Jenis Surat</th>
                                <th width="25%">Deskripsi</th>
                                <th width="15%">Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jenisSurat as $index => $jenis)
                                <tr>
                                    <td class="text-center fw-bold">{{ $jenisSurat->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <i class="fas fa-file-alt text-primary" style="font-size: 0.875rem;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold" style="font-size: 0.875rem;">{{ $jenis->nama_surat }}</div>
                                                @if($jenis->persyaratan)
                                                    <small class="text-muted" style="font-size: 0.75rem;">
                                                        <i class="fas fa-info-circle me-1" style="font-size: 0.7rem;"></i>
                                                        {{ Str::limit($jenis->persyaratan, 50) }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted" style="font-size: 0.8rem;">
                                            {{ Str::limit($jenis->deskripsi, 60) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($jenis->aktif)
                                            <span class="badge badge-admin bg-success">
                                                <i class="fas fa-check-circle me-1" style="font-size: 0.7rem;"></i>Aktif
                                            </span>
                                        @else
                                            <span class="badge badge-admin bg-danger">
                                                <i class="fas fa-times-circle me-1" style="font-size: 0.7rem;"></i>Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.jenis-surat.edit', $jenis->id) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.jenis-surat.destroy', $jenis->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus jenis surat ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <div class="h5">Belum ada jenis surat</div>
                                            <p class="mb-0">Klik tombol "Tambah Jenis Surat" untuk menambahkan data pertama</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($jenisSurat->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        <div class="pagination-wrapper">
                            {{ $jenisSurat->appends(request()->query())->links('pagination.bootstrap-4-custom') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

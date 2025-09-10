@extends('layouts.admin')

@section('title', 'Tambah Jenis Surat - Admin SKPD')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card admin-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Tambah Jenis Surat
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.jenis-surat.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="nama_surat" class="form-label fw-semibold">
                            <i class="fas fa-file-alt me-1"></i>Nama Jenis Surat
                        </label>
                        <input type="text" 
                               class="form-control form-control-admin @error('nama_surat') is-invalid @enderror" 
                               id="nama_surat" 
                               name="nama_surat" 
                               value="{{ old('nama_surat') }}" 
                               placeholder="Masukkan nama jenis surat"
                               required>
                        @error('nama_surat')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="deskripsi" class="form-label fw-semibold">
                            <i class="fas fa-align-left me-1"></i>Deskripsi
                        </label>
                        <textarea class="form-control form-control-admin @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" 
                                  name="deskripsi" 
                                  rows="3" 
                                  placeholder="Masukkan deskripsi jenis surat">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="persyaratan" class="form-label fw-semibold">
                            <i class="fas fa-list me-1"></i>Persyaratan
                        </label>
                        <textarea class="form-control form-control-admin @error('persyaratan') is-invalid @enderror" 
                                  id="persyaratan" 
                                  name="persyaratan" 
                                  rows="4" 
                                  placeholder="Masukkan persyaratan (pisahkan dengan koma)">{{ old('persyaratan') }}</textarea>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Pisahkan setiap persyaratan dengan koma (,)
                        </div>
                        @error('persyaratan')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="aktif" 
                                   name="aktif" 
                                   value="1" 
                                   {{ old('aktif', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="aktif">
                                <i class="fas fa-toggle-on me-1"></i>Status Aktif
                            </label>
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Jenis surat yang aktif akan ditampilkan di halaman pengajuan
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.jenis-surat.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-admin-primary">
                            <i class="fas fa-save me-2"></i>Simpan Jenis Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

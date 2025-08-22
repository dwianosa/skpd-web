@extends('layouts.app')

@section('title', 'Ajukan Surat - SKPD Kominfo Bukittinggi')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-file-signature"></i> 
                        Formulir Pengajuan Surat SKPD
                    </h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Jenis Surat -->
                        <div class="mb-4">
                            <label for="jenis_surat_id" class="form-label fw-bold">
                                <i class="fas fa-list"></i> Jenis Surat SKPD <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('jenis_surat_id') is-invalid @enderror" 
                                    id="jenis_surat_id" name="jenis_surat_id" required>
                                <option value="">-- Pilih Jenis Surat SKPD --</option>
                                @foreach($jenisSurat as $jenis)
                                    <option value="{{ $jenis->id }}" 
                                            {{ old('jenis_surat_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama_surat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_surat_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Data Pemohon -->
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-user"></i> Data Pemohon
                        </h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_pemohon" class="form-label fw-bold">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nama_pemohon') is-invalid @enderror" 
                                       id="nama_pemohon" name="nama_pemohon" 
                                       value="{{ old('nama_pemohon') }}" required>
                                @error('nama_pemohon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nik" class="form-label fw-bold">
                                    NIK <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nik') is-invalid @enderror" 
                                       id="nik" name="nik" 
                                       value="{{ old('nik') }}" 
                                       maxlength="16" required>
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-bold">
                                Alamat Lengkap <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="no_telp" class="form-label fw-bold">
                                    No. Telepon <span class="text-danger">*</span>
                                </label>
                                <input type="tel" 
                                       class="form-control @error('no_telp') is-invalid @enderror" 
                                       id="no_telp" name="no_telp" 
                                       value="{{ old('no_telp') }}" required>
                                @error('no_telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" 
                                       value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Keperluan -->
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-clipboard"></i> Detail Keperluan
                        </h5>

                        <div class="mb-3">
                            <label for="keperluan" class="form-label fw-bold">
                                Keperluan/Tujuan Surat <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('keperluan') is-invalid @enderror" 
                                      id="keperluan" name="keperluan" rows="4" 
                                      placeholder="Jelaskan keperluan dan tujuan penggunaan surat ini..." required>{{ old('keperluan') }}</textarea>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload File -->
                        <div class="mb-4">
                            <label for="file_pendukung" class="form-label fw-bold">
                                File Pendukung (Opsional)
                            </label>
                            <input type="file" 
                                   class="form-control @error('file_pendukung') is-invalid @enderror" 
                                   id="file_pendukung" name="file_pendukung" 
                                   accept=".pdf,.jpg,.jpeg,.png">
                            @error('file_pendukung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Format yang didukung: PDF, JPG, JPEG, PNG. Maksimal 2MB
                            </small>
                        </div>

                        <hr class="my-4">

                        <!-- Pernyataan -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pernyataan" required>
                                <label class="form-check-label" for="pernyataan">
                                    <small>
                                        Saya menyatakan bahwa data yang saya berikan adalah benar dan dapat dipertanggungjawabkan. 
                                        Apabila di kemudian hari terbukti data yang saya berikan tidak benar, saya bersedia 
                                        menerima sanksi sesuai ketentuan yang berlaku.
                                    </small>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Kirim Pengajuan
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
    // Format NIK input
    $('#nik').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        $(this).val(value);
    });
    
    // Format phone number
    $('#no_telp').on('input', function() {
        var value = $(this).val().replace(/[^\d\+\-\s]/g, '');
        $(this).val(value);
    });
    
    // File upload validation
    $('#file_pendukung').change(function() {
        var file = this.files[0];
        if (file) {
            var fileSize = file.size / 1024 / 1024; // Convert to MB
            var allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            
            if (fileSize > 2) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                $(this).val('');
                return;
            }
            
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung! Gunakan PDF, JPG, JPEG, atau PNG.');
                $(this).val('');
                return;
            }
        }
    });
});
</script>
@endpush
@endsection
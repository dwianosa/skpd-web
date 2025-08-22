@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tambah Jenis Surat</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.jenis-surat.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nama_surat" class="form-label">Nama Jenis Surat</label>
                            <input type="text" class="form-control @error('nama_surat') is-invalid @enderror" 
                                   id="nama_surat" name="nama_surat" value="{{ old('nama_surat') }}" required>
                            @error('nama_surat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.jenis-surat.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

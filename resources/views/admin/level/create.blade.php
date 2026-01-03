@extends('dashboard.layout.master')
@section('title', 'Data Level | Simonjar Payakumbuh')
@section('menuDataAutentikasi', 'active')
@section('menuDataLevel', 'active')

@section('content')
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1>Tambah Data Level</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin-level.index') }}">Data Level</a></li>
                <li class="breadcrumb-item active">Tambah Data Level</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-level.store') }}"
                      method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>ID Level</b></label>
                                        <input type="text"
                                               name="id_level"
                                               class="form-control @error('id_level') is-invalid @enderror"
                                               value="{{ old('id_level') }}"
                                               placeholder="Masukan id level">
                                        @error('id_level')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Nama Level</b></label>
                                        <input type="text"
                                               name="nm_level"
                                               class="form-control @error('nm_level') is-invalid @enderror"
                                               value="{{ old('nm_level') }}"
                                               placeholder="Masukan nama level">
                                        @error('nm_level')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label><b>Keterangan</b></label>
                                        <textarea name="keterangan"
                                                  class="form-control @error('keterangan') is-invalid @enderror"
                                                  placeholder="Masukan keterangan" rows="5">{{ old('keterangan') }}</textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i>
                                Simpan Data
                            </button>
                            <a href="{{ route('admin-level.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

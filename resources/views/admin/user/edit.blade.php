@extends('dashboard.layout.master')
@section('title', 'Data User Registrasi | Simonjar Payakumbuh')
@section('menuDataAutentikasi', 'active')
@section('menuDataUserRegistrasi', 'active')

@section('content')
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1>Edit Data User Registrasi</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin-users.index') }}">Data User Registrasi</a></li>
                <li class="breadcrumb-item active">Edit Data User Registrasi</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-users.update', $users->id ?? '') }}"
                      method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Nama Lengkap</b></label>
                                        <input type="text"
                                               name="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name', $users->name ?? '-') }}"
                                               placeholder="Masukan nama lengkap">
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Email</b></label>
                                        <input type="email"
                                               name="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email', $users->email ?? '-') }}"
                                               placeholder="Masukan alamat email">
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Telepon</b></label>
                                        <input type="text"
                                               name="telp"
                                               class="form-control @error('telp') is-invalid @enderror"
                                               value="{{ old('telp', $users->telp ?? '0') }}"
                                               placeholder="Masukan nomor telepon">
                                        @error('telp')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Instansi</b></label>
                                        <select name="instansi_id"
                                                class="form-control @error('instansi_id') is-invalid @enderror"
                                                id="selectedInstansi"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Instansi</option>
                                            @foreach ($instansis as $data)
                                                <option value="{{ $data->id ?? '-' }}"
                                                        {{ old('instansi_id', $users->instansi_id) == $data->id ? 'selected' : '' }}>{{ $data->nm_instansi ?? '-' }}</option>
                                            @endforeach
                                        </select>
                                        @error('instansi_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Level</b></label>
                                        <select name="level_id"
                                                class="form-control @error('level_id') is-invalid @enderror"
                                                id="selectedLevel"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Level</option>
                                            @foreach ($levels as $data)
                                                <option value="{{ $data->id ?? '-' }}"
                                                        {{ old('level_id', $users->level_id) == $data->id ? 'selected' : '' }}>{{ $data->nm_level ?? '-' }}</option>
                                            @endforeach
                                        </select>
                                        @error('level_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit"
                                    class="btn btn-success">
                                <i class="fas fa-save"></i>
                                Simpan Data
                            </button>
                            <a href="{{ route('admin-users.index') }}"
                               class="btn btn-primary">
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
@push('custom-script')
    <script>
        //Initialize Select2 Elements
        $('#selectedInstansi').select2({
            theme: 'bootstrap4'
        });
        $('#selectedLevel').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush

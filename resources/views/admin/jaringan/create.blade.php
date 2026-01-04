@extends('dashboard.layout.master')
@section('title', 'Data Jaringan | Simonjar Payakumbuh')
@section('menuDataJaringan', 'active')

@section('content')
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1>Tambah Data Jaringan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin-jaringan.index') }}">Data Jaringan</a></li>
                <li class="breadcrumb-item active">Tambah Data Jaringan</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-jaringan.store') }}"
                      method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
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
                                                        {{ old('instansi_id') == $data->id ? 'selected' : '' }}>{{ $data->nm_instansi ?? '-' }}</option>
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
                                        <label><b>Jenis Jaringan</b></label>
                                        <input type="text"
                                               name="jns_jaringan"
                                               class="form-control @error('jns_jaringan') is-invalid @enderror"
                                               value="{{ old('jns_jaringan') }}"
                                               placeholder="Cth: Fo, Wireless, VPN, dll">
                                        @error('jns_jaringan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Provider</b></label>
                                        <input type="text"
                                               name="provider"
                                               class="form-control @error('provider') is-invalid @enderror"
                                               value="{{ old('provider') }}"
                                               placeholder="Masukan provider">
                                        @error('provider')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Ip Address</b></label>
                                        <input type="text"
                                               name="ip_address"
                                               class="form-control @error('ip_address') is-invalid @enderror"
                                               value="{{ old('ip_address') }}"
                                               placeholder="Masukan alamat ip">
                                        @error('ip_address')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Bandwidth</b></label>
                                        <input type="text"
                                               name="bandwidth"
                                               class="form-control @error('bandwidth') is-invalid @enderror"
                                               value="{{ old('bandwidth') }}"
                                               placeholder="Masukan bandwidth">
                                        @error('bandwidth')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Status</b></label>
                                        <select name="status"
                                                class="form-control @error('status') is-invalid @enderror"
                                                id="selectedStatus"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Status</option>
                                            <option value="Normal"
                                                    {{ old('status') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                            <option value="Bermasalah"
                                                    {{ old('status') == 'Bermasalah' ? 'selected' : '' }}>Bermasalah</option>
                                        </select>
                                        @error('status')
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
                                                  rows="5"
                                                  placeholder="Masukan keterangan">{{ old('keterangan') }}</textarea>
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
                            <button type="submit"
                                    class="btn btn-success">
                                <i class="fas fa-save"></i>
                                Simpan Data
                            </button>
                            <a href="{{ route('admin-jaringan.index') }}"
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
        $('#selectedStatus').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush

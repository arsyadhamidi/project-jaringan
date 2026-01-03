@extends('dashboard.layout.master')
@section('title', 'Data Status | Simonjar Payakumbuh')
@section('menuDataStatusLaporan', 'active')

@section('content')
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1>Edit Data Status</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin-status.index') }}">Data Status</a></li>
                <li class="breadcrumb-item active">Edit Data Status</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-status.update', $status->id ?? '') }}"
                      method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Nama Status</b></label>
                                        <select name="nm_status"
                                                class="form-control @error('nm_status') is-invalid @enderror"
                                                id="selectedNama"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Status</option>
                                            <option value="Baru"
                                                    {{ old('nm_status', $status->nm_status) == 'Baru' ? 'selected' : '' }}>Baru</option>
                                            <option value="Diproses"
                                                    {{ old('nm_status', $status->nm_status) == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="Selesai"
                                                    {{ old('nm_status', $status->nm_status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="Ditolak"
                                                    {{ old('nm_status', $status->nm_status) == 'Ditolak' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                        @error('nm_status')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Warna</b></label>
                                        <select name="warna"
                                                class="form-control @error('warna') is-invalid @enderror"
                                                id="selectedWarna"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Warna</option>
                                            <option value="1"
                                                    {{ old('warna', $status->warna) == '1' ? 'selected' : '' }}>
                                                Biru
                                            </option>
                                            <option value="2"
                                                    {{ old('warna', $status->warna) == '2' ? 'selected' : '' }}>
                                                Kuning
                                            </option>
                                            <option value="3"
                                                    {{ old('warna', $status->warna) == '3' ? 'selected' : '' }}>
                                                Hijau
                                            </option>
                                            <option value="0"
                                                    {{ old('warna', $status->warna) == '0' ? 'selected' : '' }}>
                                                Merah
                                            </option>
                                        </select>
                                        @error('warna')
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
                            <a href="{{ route('admin-status.index') }}"
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
        $('#selectedWarna').select2({
            theme: 'bootstrap4'
        });
        $('#selectedNama').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush

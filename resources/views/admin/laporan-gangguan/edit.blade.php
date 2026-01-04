@extends('dashboard.layout.master')
@section('title', 'Data Laporan Gangguan | Simonjar Payakumbuh')
@section('menuDataLaporanGangguan', 'active')

@section('content')
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1>Edit Data Laporan Gangguan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin-laporangangguan.index') }}">Data Laporan Gangguan</a></li>
                <li class="breadcrumb-item active">Edit Data Laporan Gangguan</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-laporangangguan.update', $laporans->id ?? '') }}"
                      method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                {{--  Petugas  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Petugas</b></label>
                                        <select name="users_id"
                                                class="form-control @error('users_id') is-invalid @enderror"
                                                id="selectedUsers"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Petugas</option>
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id ?? '' }}"
                                                        {{ old('users_id', $laporans->users_id) == $data->id ? 'selected' : '' }}>
                                                    {{ $data->name ?? '-' }}, {{ $data->nm_instansi ?? '-' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('users_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Jaringan  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Jaringan</b></label>
                                        <select name="jaringan_id"
                                                class="form-control @error('jaringan_id') is-invalid @enderror"
                                                id="selectedJaringan"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Jaringan</option>
                                            @foreach ($jaringans as $data)
                                                <option value="{{ $data->id ?? '' }}"
                                                        {{ old('jaringan_id', $laporans->jaringan_id) == $data->id ? 'selected' : '' }}>
                                                    {{ $data->tipe_jaringan ?? '-' }}, {{ $data->nm_instansi ?? '-' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('jaringan_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Judul  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Judul</b></label>
                                        <input type="text"
                                               name="judul"
                                               class="form-control @error('judul') is-invalid @enderror"
                                               value="{{ old('judul', $laporans->judul ?? '-') }}"
                                               placeholder="Masukan judul">
                                        @error('judul')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Waktu Kejadian  --}}
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label><b>Tanggal</b></label>
                                        <input type="text"
                                               name="tanggal"
                                               class="form-control @error('tanggal') is-invalid @enderror"
                                               value="{{ old('tanggal', $laporans->waktu_kejadian ? \Carbon\Carbon::parse($laporans->waktu_kejadian)->format('Y-m-d') : Carbon\Carbon::now()->format('Y-m-d')) }}"
                                               placeholder="Masukan tanggal"
                                               id="tanggalLaporanGangguan">
                                        @error('tanggal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Waktu Kejadian  --}}
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label><b>Jam</b></label>
                                        <input type="text"
                                               name="jam"
                                               class="form-control @error('jam') is-invalid @enderror"
                                               value="{{ old('jam', $laporans->waktu_kejadian ? \Carbon\Carbon::parse($laporans->waktu_kejadian)->format('H:i:s') : \Carbon\Carbon::now()->format('H:i:s')) }}"
                                               placeholder="Masukan jam"
                                               id="jamLaporanGangguan">
                                        @error('jam')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Status  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Status</b></label>
                                        <select name="status_id"
                                                class="form-control @error('status_id') is-invalid @enderror"
                                                id="selectedStatus"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Status</option>
                                            @foreach ($status as $data)
                                                <option value="{{ $data->id ?? '' }}"
                                                        {{ old('status_id', $laporans->status_id) == $data->id ? 'selected' : '' }}>
                                                    {{ $data->nm_status ?? '-' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Prioritas  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label><b>Prioritas</b></label>
                                        <select name="prioritas"
                                                class="form-control @error('prioritas') is-invalid @enderror"
                                                id="selectedPrioritas"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Prioritas</option>
                                            <option value="Rendah"
                                                    {{ old('prioritas', $laporans->prioritas) == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                                            <option value="Sedang"
                                                    {{ old('prioritas', $laporans->prioritas) == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                            <option value="Tinggi"
                                                    {{ old('prioritas', $laporans->prioritas) == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                                        </select>
                                        @error('prioritas')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Deskripsi  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label><b>Deskripsi</b></label>
                                        <textarea name="deskripsi"
                                                  class="form-control @error('deskripsi') is-invalid @enderror"
                                                  rows="5"
                                                  placeholder="Masukan deskripsi">{{ old('deskripsi', $laporans->deskripsi ?? '-') }}</textarea>
                                        @error('deskripsi')
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
                            <a href="{{ route('admin-laporangangguan.index') }}"
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
        $('#selectedUsers').select2({
            theme: 'bootstrap4'
        });

        $('#selectedJaringan').select2({
            theme: 'bootstrap4'
        });

        $('#selectedPrioritas').select2({
            theme: 'bootstrap4'
        });

        $('#selectedStatus').select2({
            theme: 'bootstrap4'
        });

        // Datepicker
        $("#tanggalLaporanGangguan").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom auto",
            language: "id"
        }).on('show', function() {
            $('.datepicker').hide().fadeIn(200);
        });

        // Timepicker
        $("#jamLaporanGangguan").timepicker({
            showMeridian: false,
            defaultTime: false,
            minuteStep: 1,
            icons: {
                up: 'bi bi-chevron-up',
                down: 'bi bi-chevron-down'
            }
        });
    </script>
@endpush

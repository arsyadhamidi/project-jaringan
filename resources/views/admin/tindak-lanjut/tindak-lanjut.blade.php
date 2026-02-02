@extends('dashboard.layout.master')
@section('title', 'Data Tindak Lanjut | Simonjar Payakumbuh')
@section('menuDataTindakLanjut', 'active')

@section('content')
    <div class="row mb-3">
        <div class="col-sm-6">
            <div class="mb-4">
                <h1>Data Tindak Lanjut</h1>
            </div>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Data Tindak Lanjut</li>
            </ol>
        </div>
        <div class="col-lg-12">
            <div class="mb-4">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('admin-tindaklanjut.index') }}"
                           class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table"
                               style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Keterangan</th>
                                    <th>Permasalahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                            <tbody>
                                <tr>
                                    <td>Instansi</td>
                                    <td>{{ $laporans->nm_instansi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Tipe Jaringan</td>
                                    <td>
                                        {{ $laporans->tipe_jaringan ?? '-' }},
                                    </td>
                                </tr>
                                <tr>
                                    <td>Mac Address</td>
                                    <td>
                                        {{ $laporans->provider ?? '-' }},
                                    </td>
                                </tr>
                                <tr>
                                    <td>IP Address</td>
                                    <td>
                                        {{ $laporans->ip_address ?? '-' }},
                                    </td>
                                </tr>
                                <tr>
                                    <td>Bandwidth</td>
                                    <td>
                                        {{ $laporans->bandwidth ?? '-' }},
                                    </td>
                                </tr>
                                <tr>
                                    <td>Keterangan Jaringan</td>
                                    <td>
                                        {{ $laporans->keterangan ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Petugas</td>
                                    <td>
                                        {{ $laporans->name ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Judul</td>
                                    <td>
                                        {{ $laporans->judul ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>
                                        {{ $laporans->deskripsi ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        @if ($laporans->warna == '1')
                                            <span class="badge badge-primary">{{ $laporans->nm_status ?? '-' }}</span>
                                        @elseif($laporans->warna == '2')
                                            <span class="badge badge-warning">{{ $laporans->nm_status ?? '-' }}</span>
                                        @elseif ($laporans->warna == '3')
                                            <span class="badge badge-success">{{ $laporans->nm_status ?? '-' }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ $laporans->nm_status ?? '-' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="mb-4">
                <div class="card">
                    <div class="card-header">
                        <!-- Button trigger modal -->
                        <button type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalTindakLanjutTambah">
                            <i class="fas fa-plus"></i>
                            Tambah Tindak Lanjut
                        </button>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped"
                               id="myTable"
                               style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 4%">No.</th>
                                    <th>Petugas</th>
                                    <th>Waktu Lapor</th>
                                    <th>Waktu Selesai</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tindakans as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->name ?? '-' }}</td>
                                        <td>{{ $data->waktu_kejadian ?? '-' }}</td>
                                        <td>{{ $data->created_at ?? '-' }}</td>
                                        <td>{{ $data->keterangan ?? '-' }}</td>
                                        <td>
                                            @if ($data->warna == '1')
                                                <span class="badge badge-primary">{{ $data->nm_status ?? '-' }}</span>
                                            @elseif($data->warna == '2')
                                                <span class="badge badge-warning">{{ $data->nm_status ?? '-' }}</span>
                                            @elseif ($data->warna == '3')
                                                <span class="badge badge-success">{{ $data->nm_status ?? '-' }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ $data->nm_status ?? '-' }}</span>
                                            @endif
                                        </td>
                                        <td class="d-flex text-right">
                                            <!-- Button trigger modal -->
                                            <button type="button"
                                                    class="btn btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalTindakLanjutEdit{{ $data->id ?? '' }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('admin-tindaklanjut.destroytindakan', $data->id ?? '') }}"
                                                  method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-danger mx-2"
                                                        onclick="return confirm('Apakah anda yakin untuk menghapus data ini ?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>

                                            {{--  Modal  --}}
                                            <!-- Modal -->
                                            <div class="modal fade"
                                                 id="modalTindakLanjutEdit{{ $data->id ?? '' }}"
                                                 aria-labelledby="modalTindakLanjutEditLabel"
                                                 aria-hidden="true">
                                                <form action="{{ route('admin-tindaklanjut.updatetindakan', $data->id ?? '') }}"
                                                      method="POST">
                                                    @csrf
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="modalTindakLanjutTambahLabel">Edit Tindak Lanjut</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="mb-3">
                                                                            <label>Petugas</label>
                                                                            <select name="users_id"
                                                                                    class="form-control select2-users @error('users_id') is-invalid @enderror"
                                                                                    id="selectedUsersEdit{{ $data->id ?? '' }}"
                                                                                    style="width: 100%">
                                                                                <option value=""
                                                                                        selected>Pilih Petugas</option>
                                                                                @foreach ($users as $user)
                                                                                    <option value="{{ $user->id ?? '' }}"
                                                                                            {{ old('users_id', $data->users_id) == $user->id ? 'selected' : '' }}>{{ $user->name ?? '-' }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('users_id')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="mb-3">
                                                                            <label>Status</label>
                                                                            <select name="status_id"
                                                                                    class="form-control select2-status @error('status_id') is-invalid @enderror"
                                                                                    id="selectedStatusEdit{{ $data->id ?? '' }}"
                                                                                    style="width: 100%">
                                                                                <option value=""
                                                                                        selected>Pilih Status</option>
                                                                                @foreach ($status as $statuss)
                                                                                    <option value="{{ $statuss->id ?? '' }}"
                                                                                            {{ old('status_id', $data->status_id) == $statuss->id ? 'selected' : '' }}>{{ $statuss->nm_status ?? '-' }}
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
                                                                    <div class="col-lg-12">
                                                                        <div class="mb-3">
                                                                            <label>Keterangan</label>
                                                                            <textarea name="keterangan"
                                                                                      class="form-control @error('keterangan') is-invalid @enderror"
                                                                                      rows="5"
                                                                                      placeholder="Masukan keterangan">{{ old('keterangan', $data->keterangan ?? '-') }}</textarea>
                                                                            @error('keterangan')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button"
                                                                        class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                <button type="submit"
                                                                        class="btn btn-success">Simpan Data</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        {{--  Modal  --}}
        <!-- Modal -->
        <div class="modal fade"
             id="modalTindakLanjutTambah"
             aria-labelledby="modalTindakLanjutTambahLabel"
             aria-hidden="true">
            <form action="{{ route('admin-tindaklanjut.storetindakan') }}"
                  method="POST">
                @csrf
                <input type="hidden"
                       name="laporan_id"
                       value="{{ $laporans->id ?? '-' }}">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"
                                id="modalTindakLanjutTambahLabel">Tambah Tindak Lanjut</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Petugas</label>
                                        <select name="users_id"
                                                class="form-control @error('users_id') is-invalid @enderror"
                                                id="selectedUsersTambah"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Petugas</option>
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id ?? '' }}"
                                                        {{ old('users_id') == $data->id ? 'selected' : '' }}>{{ $data->name ?? '-' }}</option>
                                            @endforeach
                                        </select>
                                        @error('users_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select name="status_id"
                                                class="form-control @error('status_id') is-invalid @enderror"
                                                id="selectedStatusTambah"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Status</option>
                                            @foreach ($status as $data)
                                                <option value="{{ $data->id ?? '' }}"
                                                        {{ old('status_id') == $data->id ? 'selected' : '' }}>{{ $data->nm_status ?? '-' }}</option>
                                            @endforeach
                                        </select>
                                        @error('status_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Keterangan</label>
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
                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">Tutup</button>
                            <button type="submit"
                                    class="btn btn-success">Simpan Data</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>

@endsection
@push('custom-script')
    <script>
        $('#selectedStatusTambah').select2({
            theme: 'bootstrap4'
        });

        $('#selectedUsersTambah').select2({
            theme: 'bootstrap4'
        });
    </script>
    <script>
        $(document).on('shown.bs.modal', '.modal', function() {
            let modal = $(this);

            modal.find('.select2-users').select2({
                theme: 'bootstrap4',
                dropdownParent: modal,
                width: '100%'
            });

            modal.find('.select2-status').select2({
                theme: 'bootstrap4',
                dropdownParent: modal,
                width: '100%'
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            @if (Session::has('success'))
                toastr.success("{{ Session::get('success') }}");
            @endif

            @if (Session::has('error'))
                toastr.error("{{ Session::get('error') }}");
            @endif
        });
    </script>
@endpush

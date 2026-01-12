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
                                    <td>Provider</td>
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
                                            <span class="badge badge-dangers">{{ $laporans->nm_status ?? '-' }}</span>
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
    </div>

@endsection

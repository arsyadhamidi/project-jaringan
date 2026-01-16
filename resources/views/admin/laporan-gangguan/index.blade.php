@extends('dashboard.layout.master')
@section('title', 'Data Laporan Gangguan | Simonjar Payakumbuh')
@section('menuDataLaporanGangguan', 'active')

@section('content')
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1>Data Laporan Gangguan</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="#"
               class="btn btn-success"
               target="_blank"
               id="generateexcel">
               <i class="fas fa-download"></i>
               Download Excel
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <div class="card">
                    <div class="card-header">
                        <b>Filter Laporan Gangguan</b>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <input type="text"
                                           class="form-control"
                                           id="searchByDate">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <select name="status_id"
                                            class="form-control"
                                            id="selectedStatus"
                                            style="width: 100%">
                                        <option value=""
                                                selected>Pilih Status</option>
                                        @foreach ($status as $data)
                                            <option value="{{ $data->id ?? '' }}">{{ $data->nm_status ?? '-' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('admin-laporangangguan.create') }}"
                           class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Tambah
                        </a>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped"
                               id="myTable"
                               style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 4%">No.</th>
                                    <th>Petugas</th>
                                    <th>Instansi</th>
                                    <th>Jaringan</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Prioritas</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-script')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $('#selectedStatus').select2({
                theme: 'bootstrap4'
            });

            var startDate = moment().startOf('month');
            var endOfDate = moment().endOf('month');

            $('#searchByDate').daterangepicker({
                startDate: startDate,
                endDate: endOfDate,
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: 'Terapkan',
                    cancelLabel: 'Batal',
                    customRangeLabel: 'Pilih Rentang Tanggal',
                    daysOfWeek: [
                        'Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'
                    ],
                    monthNames: [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ],
                    firstDay: 1
                },
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start_date, end_date) {
                myTable.draw();
            });

            // Tampilkan Data
            let myTable = $("#myTable").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, 250],
                    [10, 25, 50, 100, 250],
                ],
                language: {
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya",
                    },
                },
                ajax: {
                    url: "{{ route('admin-laporangangguan.index') }}",
                    data: function(data) {
                        data.page = Math.ceil(data.start / data.length) + 1;
                        data.search = $("#myTable_filter input").val();

                        var startDate = $('#searchByDate').data('daterangepicker').startDate.format(
                            'YYYY-MM-DD');
                        var endDate = $('#searchByDate').data('daterangepicker').endDate.format(
                            'YYYY-MM-DD');

                        data.start_date = startDate;
                        data.end_date = endDate;
                        data.status_id = $('#selectedStatus').val();

                        // Memperbarui URL untuk mengunduh Excel
                        $('#generateexcel').attr('href', "{{ route('admin-laporangangguan.generateExcel') }}" +
                            "?start_date=" + startDate + "&end_date=" + endDate +
                            "&status_id=" + data.status_id);
                    },
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                    },
                    {
                        data: "name",
                        name: "name",
                        defaultContent: "-",
                    },
                    {
                        data: "nm_instansi",
                        name: "nm_instansi",
                        defaultContent: "-",
                    },
                    {
                        data: "tipe_jaringan",
                        name: "tipe_jaringan",
                        defaultContent: "-",
                    },
                    {
                        data: "judul",
                        name: "judul",
                        defaultContent: "-",
                    },
                    {
                        data: "deskripsi",
                        name: "deskripsi",
                        defaultContent: "-",
                    },
                    {
                        data: "prioritas",
                        name: "prioritas",
                        defaultContent: "-",
                    },
                    {
                        data: "waktu_kejadian",
                        name: "waktu_kejadian",
                        defaultContent: "-",
                    },
                    {
                        data: "warna",
                        name: "warna",
                        defaultContent: "-",
                        render: function(data, type, row) {
                            var namaStatus = row.nm_status;
                            if (data == '1') {
                                return '<span class="badge badge-primary">' + namaStatus + '</span>';
                            } else if (data == '2') {
                                return '<span class="badge badge-warning">' + namaStatus + '</span>';
                            } else if (data == '3') {
                                return '<span class="badge badge-success">' + namaStatus + '</span>';
                            } else if (data == '0') {
                                return '<span class="badge badge-danger">' + namaStatus + '</span>';
                            }
                            return data ?? '-';
                        }
                    },
                    {
                        data: "aksi",
                        name: "aksi",
                        orderable: false,
                        searchable: false,
                        defaultContent: "-",
                    },
                ],

                order: [
                    [1, "desc"]
                ],
            });

            $('#selectedStatus').on('change', function() {
                myTable.ajax.reload();
            });

            // === Event Listener untuk Tombol Hapus User ===
            $("#myTable").on("click", ".btn-delete", function() {
                const resultid = $(this).data("resultid");

                if (!confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    return;
                }

                $.ajax({
                    url: "/admin-laporangangguan/destroy/" + resultid, // route('admin-users.destroy', id)
                    type: "POST", // Laravel destroy biasanya pakai method DELETE
                    data: {
                        _token: "{{ csrf_token() }}", // wajib untuk keamanan Laravel
                    },
                    success: function(res) {
                        if (res.status === "success") {
                            toastr.success(res.message || "Data berhasil dihapus!");
                            myTable.ajax.reload(null, false); // reload tabel tanpa reset pagination
                        } else {
                            toastr.warning(res.message || "Gagal menghapus data.");
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Terjadi kesalahan: " + xhr.responseText);
                    },
                });
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

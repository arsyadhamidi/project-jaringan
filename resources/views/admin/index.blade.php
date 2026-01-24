<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Beranda</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
            <li class="breadcrumb-item active">Main Page</li>
        </ol>
    </div>
</div>

<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $countInstansis ?? '0' }}</h3>

                <p>Data Instansi</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="{{ route('admin-instansi.index') }}"
               class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $countJaringans ?? '0' }}</h3>

                <p>Data Jaringan</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{ route('admin-jaringan.index') }}"
               class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $countLaporans ?? '0' }}</h3>

                <p>Data Laporan Gangguan</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ route('admin-laporangangguan.index') }}"
               class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $countTindakans ?? '0' }}</h3>

                <p>Data Tindakan Lanjut</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="{{ route('admin-tindaklanjut.index') }}"
               class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Laporan Gangguan
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
                            <th>Status Jaringan</th>
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

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    Grafik Laporan Gangguan & Tindak Lanjut
                </h3>
            </div>
            <div class="card-body">
                <canvas id="laporanChart"
                        height="500"></canvas>
            </div>
        </div>
    </div>
</div>

@php
    $bulan = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'Mei',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Agu',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Des',
    ];

    $dataLaporan = [];
    $dataTindakan = [];

    foreach ($bulan as $key => $value) {
        $dataLaporan[] = $laporanPerBulan[$key] ?? 0;
        $dataTindakan[] = $tindakanPerBulan[$key] ?? 0;
    }
@endphp


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
                    url: "{{ route('dashboard.index') }}",
                    data: function(data) {
                        data.page = Math.ceil(data.start / data.length) + 1;
                        data.search = $("#myTable_filter input").val();
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
                        data: "status",
                        name: "status",
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
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('laporanChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_values($bulan)) !!},
                    datasets: [{
                            label: 'Laporan Gangguan',
                            data: {!! json_encode($dataLaporan) !!},
                            backgroundColor: 'rgba(255, 193, 7, 0.8)'
                        },
                        {
                            label: 'Tindak Lanjut',
                            data: {!! json_encode($dataTindakan) !!},
                            backgroundColor: 'rgba(220, 53, 69, 0.8)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });
        });
    </script>
@endpush

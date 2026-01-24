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

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="totalOnline">0</h3>
                <p>Jaringan Online</p>
            </div>
            <div class="icon">
                <i class="fas fa-wifi"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="totalOffline">0</h3>
                <p>Jaringan Offline</p>
            </div>
            <div class="icon">
                <i class="fas fa-wifi-slash"></i>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            function loadJaringanStatus() {
                fetch('/dashboard/jaringan-status')
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('totalOnline').innerText = data.online;
                        document.getElementById('totalOffline').innerText = data.offline;
                    })
                    .catch(() => {
                        document.getElementById('totalOnline').innerText = '0';
                        document.getElementById('totalOffline').innerText = '0';
                    });
            }

            loadJaringanStatus();

            // refresh tiap 1 menit
            setInterval(loadJaringanStatus, 60000);
        });
    </script>
@endpush

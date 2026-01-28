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


@push('custom-script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            function loadJaringanStatus() {
                fetch('/dashboard/jaringan-status/opd')
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

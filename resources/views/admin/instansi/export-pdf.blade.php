<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Gangguan</title>

    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            margin: 20px 30px;
        }

        /* ================= KOP SURAT ================= */
        .kop-surat {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            width: 90px;
            text-align: center;
            vertical-align: middle;
        }

        .logo img {
            width: 75px;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
        }

        .instansi {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .dinas {
            font-size: 22px;
            font-weight: bold;
            margin-top: 2px;
        }

        .alamat {
            font-size: 11px;
            margin-top: 4px;
        }

        .garis-1 {
            border-top: 3px solid #000;
            margin-top: 6px;
        }

        .garis-2 {
            border-top: 1px solid #000;
            margin-top: 2px;
        }

        /* ================= JUDUL LAPORAN ================= */
        .judul-laporan {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px;
            text-transform: uppercase;
        }

        /* ================= TABLE DATA ================= */
        table.table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.table th,
        table.table td {
            border: 1px solid #000;
            padding: 6px 5px;
            vertical-align: top;
        }

        table.table th {
            text-align: center;
            font-weight: bold;
            background-color: #f0f0f0;
        }

        table.table td {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        /* Lebar kolom supaya rapi */
        .col-no { width: 4%; }
        .col-petugas { width: 12%; }
        .col-instansi { width: 15%; }
        .col-jaringan { width: 10%; }
        .col-judul { width: 14%; }
        .col-deskripsi { width: 25%; }
        .col-prioritas { width: 8%; }
        .col-waktu { width: 12%; }
    </style>
</head>

<body>

    <!-- KOP -->
    <table class="kop-surat">
        <tr>
            <td class="logo">
                <img src="{{ public_path('images/logo-kota.png') }}" alt="Logo">
            </td>
            <td class="kop-text">
                <div class="instansi">PEMERINTAH KOTA PAYAKUMBUH</div>
                <div class="dinas">DINAS KOMUNIKASI DAN INFORMATIKA</div>
                <div class="alamat">
                    Jl. Veteran Komplek Perkantoran Balai Kota (eks. Lapangan Poliko) Kota Payakumbuh<br>
                    Telp/Fax: (0752) 7972844 &nbsp; Email: diskominfopyk@gmail.com
                </div>
            </td>
        </tr>
    </table>

    <div class="garis-1"></div>
    <div class="garis-2"></div>

    <!-- JUDUL -->
    <div class="judul-laporan">
        Rekap Data Instansi
    </div>

    <!-- TABLE DATA -->
    <table class="table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-petugas">Nama</th>
                <th class="col-instansi">Email</th>
                <th class="col-jaringan">Telepon</th>
                <th class="col-judul">Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($instansis as $data)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $data->nm_instansi ?? '-' }}</td>
                    <td>{{ $data->email ?? '-' }}</td>
                    <td>{{ $data->telp ?? '-' }}</td>
                    <td>{{ $data->alamat ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>

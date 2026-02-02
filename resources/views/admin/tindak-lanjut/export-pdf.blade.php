<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tindak Lanjut</title>

    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            margin: 20px 30px;
        }

        /* KOP */
        .kop-surat {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            width: 90px;
            text-align: center;
        }

        .logo img {
            width: 75px;
        }

        .kop-text {
            text-align: center;
        }

        .instansi {
            font-size: 18px;
            font-weight: bold;
        }

        .dinas {
            font-size: 22px;
            font-weight: bold;
        }

        .alamat {
            font-size: 11px;
        }

        .garis-1 {
            border-top: 3px solid #000;
            margin-top: 6px;
        }

        .garis-2 {
            border-top: 1px solid #000;
            margin-top: 2px;
        }

        /* JUDUL */
        .judul-laporan {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }

        /* TABEL DETAIL */
        table.detail {
            width: 100%;
            border-collapse: collapse;
        }

        table.detail td {
            padding: 6px;
            vertical-align: top;
        }

        .label {
            width: 25%;
            font-weight: bold;
        }

        .separator {
            width: 2%;
        }

        /* TTD */
        .ttd {
            width: 100%;
            margin-top: 40px;
        }

        .ttd-kanan {
            width: 40%;
            float: right;
            text-align: center;
        }

        .nama {
            margin-top: 80px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <!-- KOP -->
    <table class="kop-surat">
        <tr>
            <td class="logo">
                <img src="{{ public_path('images/logo-kota.png') }}">
            </td>
            <td class="kop-text">
                <div class="instansi">PEMERINTAH KOTA PAYAKUMBUH</div>
                <div class="dinas">DINAS KOMUNIKASI DAN INFORMATIKA</div>
                <div class="alamat">
                    Jl. Veteran Komplek Perkantoran Balai Kota Kota Payakumbuh<br>
                    Telp/Fax: (0752) 7972844
                </div>
            </td>
        </tr>
    </table>

    <div class="garis-1"></div>
    <div class="garis-2"></div>

    <!-- JUDUL -->
    <div class="judul-laporan">
        LAPORAN TINDAK LANJUT GANGGUAN JARINGAN
    </div>

    <!-- DATA -->
    <table class="detail">
        <tr>
            <td class="label">Nama Petugas</td>
            <td class="separator">:</td>
            <td>{{ $laporans->name }}</td>
        </tr>

        <tr>
            <td class="label">Judul Laporan</td>
            <td class="separator">:</td>
            <td>{{ $laporans->judul }}</td>
        </tr>

        <tr>
            <td class="label">Deskripsi Gangguan</td>
            <td class="separator">:</td>
            <td>{{ $laporans->deskripsi }}</td>
        </tr>

        <tr>
            <td class="label">Prioritas</td>
            <td class="separator">:</td>
            <td>{{ $laporans->prioritas }}</td>
        </tr>

        <tr>
            <td class="label">Status</td>
            <td class="separator">:</td>
            <td>{{ $laporans->nm_status }}</td>
        </tr>

        <tr>
            <td class="label">Tindak Lanjut</td>
            <td class="separator">:</td>
            <td>{{ $laporans->keterangan }}</td>
        </tr>

        <tr>
            <td class="label">Waktu Kejadian</td>
            <td class="separator">:</td>
            <td>
                {{ \Carbon\Carbon::parse($laporans->waktu_kejadian)->format('d-m-Y H:i') }}
            </td>
        </tr>
        <tr>
            <td class="label">Waktu Selesai</td>
            <td class="separator">:</td>
            <td>
                {{ \Carbon\Carbon::parse($laporans->created_at)->format('d-m-Y H:i') }}
            </td>
        </tr>
    </table>

    <!-- MENGETAHUI -->
    <div class="ttd">
        <div class="ttd-kanan">
            Payakumbuh, {{ \Carbon\Carbon::now()->format('d-m-Y') }}<br>
            Mengetahui,<br>
            Kepala Dinas Komunikasi dan Informatika
            <div class="nama">
                KURNIAWAN SYAH PUTRA, S.Sos, M.AP
            </div>
            NIP. 19720402 199203 1 003
        </div>
    </div>

</body>
</html>

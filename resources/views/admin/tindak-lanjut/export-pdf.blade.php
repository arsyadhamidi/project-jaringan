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

        .judul-laporan {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }

        table.detail,
        table.tindak {
            width: 100%;
            border-collapse: collapse;
        }

        table.detail td {
            padding: 6px;
            vertical-align: top;
        }

        table.tindak th,
        table.tindak td {
            border: 1px solid #000;
            padding: 6px;
        }

        .label {
            width: 25%;
            font-weight: bold;
        }

        .separator {
            width: 2%;
        }

        .ttd {
            margin-top: 40px;
            width: 100%;
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

    <!-- JUDUL TINDAK LANJUT -->
    <b>RIWAYAT TINDAK LANJUT</b>
    <br><br>

    <!-- TABEL TINDAK LANJUT (PERULANGAN) -->
    <table class="tindak">
        <thead>
            <tr>
                <th width="5%">No</th>
               <th class="col-instansi">Instansi</th>
                <th class="col-jaringan">Jaringan</th>
                <th class="col-judul">Judul</th>
                <th class="col-deskripsi">Deskripsi</th>
                <th class="col-prioritas">Prioritas</th>
                <th class="col-waktu">Waktu Lapor</th>
                <th width="15%">Waktu Selesai</th>
                <th width="20%">Petugas</th>
                <th width="20%">Status</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tindakans as $index => $tindakan)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $tindakan->nm_instansi ?? '-' }}</td>
                    <td>{{ $tindakan->ip_address ?? '-' }}</td>
                    <td>{{ $tindakan->judul ?? '-' }}</td>
                    <td>{{ $tindakan->deskripsi ?? '-' }}</td>
                    <td class="text-center">{{ $tindakan->prioritas ?? '-' }}</td>
                    <td>{{ $tindakan->waktu_kejadian ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($tindakan->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $tindakan->name }}</td>
                    <td>{{ $tindakan->nm_status }}</td>
                    <td>{{ $tindakan->ket_tindakan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11"
                        align="center"><i>Belum ada tindak lanjut</i></td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TTD -->
    <div class="ttd">
        <div class="ttd-kanan">
            Payakumbuh, {{ now()->format('d-m-Y') }}<br>
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

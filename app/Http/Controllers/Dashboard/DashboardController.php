<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Models\Jaringan;
use App\Models\LaporanGangguan;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $countInstansis = Instansi::count();
        $countJaringans = Jaringan::count();
        $countLaporans = LaporanGangguan::count();
        $countTindakans = TindakLanjut::count();

        // Laporan Gangguan per bulan
        $laporanPerBulan = LaporanGangguan::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Tindak Lanjut per bulan
        $tindakanPerBulan = TindakLanjut::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = LaporanGangguan::join('instansis', 'laporan_gangguans.instansi_id', 'instansis.id')
                ->join('jaringans', 'laporan_gangguans.jaringan_id', 'jaringans.id')
                ->join('status_laporans', 'laporan_gangguans.status_id', 'status_laporans.id')
                ->join('users', 'laporan_gangguans.users_id', 'users.id')
                ->select([
                    'laporan_gangguans.id',
                    'laporan_gangguans.status_id',
                    'laporan_gangguans.judul',
                    'laporan_gangguans.deskripsi',
                    'laporan_gangguans.waktu_kejadian',
                    'laporan_gangguans.prioritas',
                    'instansis.nm_instansi',
                    'jaringans.tipe_jaringan',
                    'jaringans.provider',
                    'jaringans.ip_address',
                    'jaringans.bandwidth',
                    'jaringans.status',
                    'jaringans.keterangan',
                    'status_laporans.nm_status',
                    'status_laporans.warna',
                    'users.name',
                ])
                ->where('laporan_gangguans.status_id', '1')
                ->orderBy('laporan_gangguans.id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('users.name', 'LIKE', "%{$search}%")
                        ->orWhere('instansis.nm_instansi', 'LIKE', "%{$search}%");
                });
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $tindakUrl = route('admin-tindaklanjut.tindaklanjut', $item->id ?? '');

                $item->aksi = '
        <a href="' . $tindakUrl . '" class="btn btn-outline-primary me-1">
            <i class="fas fa-reply"></i>
        </a>
    ';

                return $item;
            });


            return response()->json([
                'draw' => $request->input('draw'), // Ambil nomor draw dari permintaan
                'recordsTotal' => $totalRecords, // Kirim jumlah total data
                'recordsFiltered' => $totalRecords, // Jumlah data yang difilter sama dengan jumlah total
                'data' => $dataWithActions, // Kirim data yang sesuai dengan halaman dan jumlah per halaman
            ]);
        }

        return view('dashboard.main.index', [
            'countInstansis' => $countInstansis,
            'countJaringans' => $countJaringans,
            'countLaporans' => $countLaporans,
            'countTindakans' => $countTindakans,

            'laporanPerBulan' => $laporanPerBulan,
            'tindakanPerBulan' => $tindakanPerBulan,
        ]);
    }
}

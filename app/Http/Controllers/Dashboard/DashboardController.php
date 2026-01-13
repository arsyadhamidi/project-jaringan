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
    public function index()
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

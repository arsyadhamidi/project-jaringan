<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Models\Jaringan;
use App\Models\LaporanGangguan;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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

        return view('dashboard.main.index', [
            'countInstansis' => $countInstansis,
            'countJaringans' => $countJaringans,
            'countLaporans' => $countLaporans,
            'countTindakans' => $countTindakans,

            'laporanPerBulan' => $laporanPerBulan,
            'tindakanPerBulan' => $tindakanPerBulan,
        ]);
    }

    public function jaringanStatus()
    {
        $ips = Jaringan::pluck('ip_address');

        $online = 0;
        $offline = 0;

        foreach ($ips as $ip) {

            // cache 30 detik biar server aman
            $status = Cache::remember("ping_dashboard_$ip", 30, function () use ($ip) {
                $safeIp = escapeshellarg($ip);
                exec("ping -n 1 -w 1000 $safeIp", $output, $result);

                return $result === 0 ? 'Online' : 'Offline';
            });

            if ($status === 'Online') {
                $online++;
            } else {
                $offline++;
            }
        }

        return response()->json([
            'online' => $online,
            'offline' => $offline
        ]);
    }
}

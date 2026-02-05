<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanGangguan;
use App\Models\StatusLaporan;
use App\Models\TindakLanjut;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;

class AdminTindakLanjutController extends Controller
{
    public function index(Request $request)
    {
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
                ->orderBy('laporan_gangguans.id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('users.name', 'LIKE', "%{$search}%")
                        ->orWhere('instansis.nm_instansi', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('laporan_gangguans.waktu_kejadian', [$start_date, $end_date]);
            }

            if ($request->has('status_id') && !empty($request->status_id)) {
                $query->where('laporan_gangguans.status_id', $request->status_id);
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            return response()->json([
                'draw' => $request->input('draw'), // Ambil nomor draw dari permintaan
                'recordsTotal' => $totalRecords, // Kirim jumlah total data
                'recordsFiltered' => $totalRecords, // Jumlah data yang difilter sama dengan jumlah total
                'data' => $data->items(), // Kirim data yang sesuai dengan halaman dan jumlah per halaman
            ]);
        }

        $status = StatusLaporan::orderBy('id', 'desc')->get();

        return view('admin.tindak-lanjut.index', [
            'status' => $status,
        ]);
    }

    public function tindaklanjut($id)
    {
        $laporans = LaporanGangguan::join('instansis', 'laporan_gangguans.instansi_id', 'instansis.id')
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
            ])->where('laporan_gangguans.id', $id)
            ->orderBy('laporan_gangguans.id', 'desc')
            ->first();

        $users = User::orderBy('id', 'desc')->get();
        $status = StatusLaporan::orderBy('id', 'desc')->get();
        $tindakans = TindakLanjut::join('laporan_gangguans', 'tindak_lanjuts.laporan_id', 'laporan_gangguans.id')
            ->join('status_laporans', 'tindak_lanjuts.status_id', 'status_laporans.id')
            ->join('users', 'tindak_lanjuts.users_id', 'users.id')
            ->select([
                'tindak_lanjuts.id',
                'tindak_lanjuts.laporan_id',
                'tindak_lanjuts.users_id',
                'tindak_lanjuts.status_id',
                'tindak_lanjuts.tanggal',
                'tindak_lanjuts.keterangan',
                'tindak_lanjuts.created_at',
                'tindak_lanjuts.updated_at',
                'status_laporans.nm_status',
                'status_laporans.warna',
                'laporan_gangguans.judul',
                'laporan_gangguans.deskripsi',
                'laporan_gangguans.waktu_kejadian',
                'laporan_gangguans.prioritas',
                'users.name',
            ])->where('tindak_lanjuts.laporan_id', $id)
            ->orderBy('tindak_lanjuts.id', 'desc')
            ->get();



        return view('admin.tindak-lanjut.tindak-lanjut', [
            'laporans' => $laporans,
            'users' => $users,
            'status' => $status,
            'tindakans' => $tindakans,
        ]);
    }

    public function storetindakan(Request $request)
    {
        $request->validate(
            [
                'users_id'   => 'required',
                'status_id'  => 'required',
                'tanggal'  => 'required',
                'keterangan' => 'required',
            ],
            [
                'users_id.required'   => 'Pengguna wajib dipilih.',
                'status_id.required'  => 'Status wajib dipilih.',
                'tanggal.required'  => 'Tanggal wajib diisi.',
                'keterangan.required' => 'Keterangan tidak boleh kosong.',
            ]
        );

        LaporanGangguan::where('id', $request->laporan_id)->update([
            'status_id' => $request->status_id,
        ]);

        TindakLanjut::create([
            'laporan_id' => $request->laporan_id,
            'users_id' => $request->users_id,
            'status_id' => $request->status_id,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil memberikan laporan tindak lanjut');
    }

    public function updatetindakan(Request $request, $id)
    {
        $request->validate(
            [
                'users_id'   => 'required',
                'status_id'  => 'required',
                'tanggal' => 'required',
                'keterangan' => 'required',
            ],
            [
                'users_id.required'   => 'Pengguna wajib dipilih.',
                'status_id.required'  => 'Status wajib dipilih.',
                'tanggal.required' => 'Tanggal wajib diisi.',
                'keterangan.required' => 'Keterangan tidak boleh kosong.',
            ]
        );

        $tindaks = TindakLanjut::where('id', $id)->orderBy('id', 'desc')->first();

        LaporanGangguan::where('id', $tindaks->laporan_id)->update([
            'status_id' => $request->status_id,
        ]);

        $tindaks->update([
            'users_id' => $request->users_id,
            'status_id' => $request->status_id,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil memperbaharui laporan tindak lanjut');
    }

    public function destroytindakan($id)
    {

        $tindaks = TindakLanjut::where('id', $id)->orderBy('id', 'desc')->first();

        $tindaks->delete();

        return back()->with('success', 'Selamat ! Anda berhasil menghapus laporan tindak lanjut');
    }

    public function generatepdf(Request $request)
    {
        $users = User::orderBy('id', 'desc')->get();
        $status = StatusLaporan::orderBy('id', 'desc')->get();
        $query = TindakLanjut::leftJoin('laporan_gangguans', 'tindak_lanjuts.laporan_id', 'laporan_gangguans.id')
            ->leftJoin('status_laporans', 'tindak_lanjuts.status_id', 'status_laporans.id')
            ->leftJoin('users', 'tindak_lanjuts.users_id', 'users.id')
            ->leftJoin('instansis', 'laporan_gangguans.instansi_id', 'instansis.id')
            ->leftJoin('jaringans', 'laporan_gangguans.jaringan_id', 'jaringans.id')
            ->select([
                'tindak_lanjuts.id',
                'tindak_lanjuts.laporan_id',
                'tindak_lanjuts.users_id',
                'tindak_lanjuts.tanggal',
                'tindak_lanjuts.keterangan as ket_tindakan',
                'tindak_lanjuts.created_at',
                'tindak_lanjuts.updated_at',
                'status_laporans.nm_status',
                'status_laporans.warna',
                'laporan_gangguans.judul',
                'laporan_gangguans.deskripsi',
                'laporan_gangguans.waktu_kejadian',
                'laporan_gangguans.prioritas',
                'laporan_gangguans.status_id',
                'instansis.nm_instansi',
                'jaringans.tipe_jaringan',
                'jaringans.provider',
                'jaringans.ip_address',
                'jaringans.bandwidth',
                'jaringans.status',
                'jaringans.keterangan',
                'users.name',
            ])
            ->orderBy('tindak_lanjuts.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('laporan_gangguans.waktu_kejadian', [$start_date, $end_date]);
        }

        if ($request->has('status_id') && !empty($request->status_id)) {
            $query->where('laporan_gangguans.status_id', $request->status_id);
        }

        // ⚠️ WAJIB get()
        $tindakans = $query->get();

        $pdf = PDF::loadview('admin.tindak-lanjut.export-pdf', [
            'users' => $users,
            'status' => $status,
            'tindakans' => $tindakans,
        ])->setPaper('A4', 'Potrait');;
        // return $pdf->download('laporan-gangguan.pdf');
        return $pdf->stream('tindak-lanjut.pdf');
    }
}

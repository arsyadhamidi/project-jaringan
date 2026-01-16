<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LaporanGangguanExport;
use App\Http\Controllers\Controller;
use App\Models\Jaringan;
use App\Models\LaporanGangguan;
use App\Models\StatusLaporan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminLaporanGangguanController extends Controller
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

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-laporangangguan.edit', $item->id ?? '');

                $item->aksi = '
        <a href="' . $editUrl . '" class="btn btn-outline-primary me-1">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button"
                class="btn btn-outline-danger btn-delete"
                data-resultid="' . e($resultid) . '">
            <i class="fas fa-trash-alt"></i>
        </button>
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

        $status = StatusLaporan::orderBy('id', 'desc')->get();

        return view('admin.laporan-gangguan.index', [
            'status' => $status
        ]);
    }

    public function generateExcel(Request $request)
    {
        $query = LaporanGangguan::join('instansis', 'laporan_gangguans.instansi_id', 'instansis.id')
            ->join('jaringans', 'laporan_gangguans.jaringan_id', 'jaringans.id')
            ->join('status_laporans', 'laporan_gangguans.status_id', 'status_laporans.id')
            ->join('users', 'laporan_gangguans.users_id', 'users.id')
            ->select([
                'laporan_gangguans.id',
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
                'users.email',
                'users.telp',
            ]);

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('laporan_gangguans.waktu_kejadian', [$start_date, $end_date]);
        }

        if ($request->has('status_id') && !empty($request->status_id)) {
            $query->where('laporan_gangguans.status_id', $request->status_id);
        }

        $data = $query->orderBy('laporan_gangguans.id', 'desc')->get();

        return Excel::download(new LaporanGangguanExport($data), 'data-laporan-gangguan.xlsx');
    }

    public function create()
    {
        $users = User::join('instansis', 'users.instansi_id', 'instansis.id')
            ->select([
                'users.id',
                'users.name',
                'instansis.nm_instansi',
            ])
            ->get();
        $jaringans = Jaringan::join('instansis', 'jaringans.instansi_id', 'instansis.id')
            ->select([
                'jaringans.id',
                'jaringans.tipe_jaringan',
                'instansis.nm_instansi',
            ])
            ->get();
        $status = StatusLaporan::get();
        return view('admin.laporan-gangguan.create', [
            'users' => $users,
            'status' => $status,
            'jaringans' => $jaringans,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'users_id'        => 'required',
                'jaringan_id'     => 'required',
                'status_id'       => 'required',
                'judul'           => 'required|max:100',
                'deskripsi'       => 'required',
                'prioritas'       => 'required',
            ],
            [
                'users_id.required'       => 'Pelapor wajib dipilih.',
                'jaringan_id.required'    => 'Data jaringan wajib dipilih.',
                'status_id.required'      => 'Status laporan wajib dipilih.',

                'judul.required'          => 'Judul laporan wajib diisi.',
                'judul.max'               => 'Judul laporan maksimal 100 karakter.',

                'deskripsi.required'      => 'Deskripsi gangguan wajib diisi.',

                'prioritas.required'      => 'Prioritas laporan wajib dipilih.',
            ]
        );

        $users = User::where('id', $request->users_id)->first();

        // ---- PROSES SIMPAN DATA ----
        $jams = Carbon::parse($request->jam)->format('H:i:s');
        $tanggals = Carbon::parse($request->tanggal)->format('Y-m-d');
        $dateTimes = $tanggals . ' ' . $jams;

        LaporanGangguan::create([
            'instansi_id' => $users->instansi_id,
            'jaringan_id' => $request->jaringan_id,
            'users_id' => $users->id,
            'status_id' => $request->status_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'waktu_kejadian' => $dateTimes,
            'prioritas' => $request->prioritas,
        ]);

        return redirect()->route('admin-laporangangguan.index')->with('success', 'Selamat ! Anda berhasil menambahkan data laporan gangguan');
    }

    public function edit($id)
    {
        $users = User::join('instansis', 'users.instansi_id', 'instansis.id')
            ->select([
                'users.id',
                'users.name',
                'instansis.nm_instansi',
            ])
            ->get();
        $jaringans = Jaringan::join('instansis', 'jaringans.instansi_id', 'instansis.id')
            ->select([
                'jaringans.id',
                'jaringans.tipe_jaringan',
                'instansis.nm_instansi',
            ])
            ->get();
        $status = StatusLaporan::get();
        $laporans = LaporanGangguan::where('id', $id)->first();
        return view('admin.laporan-gangguan.edit', [
            'users' => $users,
            'status' => $status,
            'jaringans' => $jaringans,
            'laporans' => $laporans,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'users_id'        => 'required',
                'jaringan_id'     => 'required',
                'status_id'       => 'required',
                'judul'           => 'required|max:100',
                'deskripsi'       => 'required',
                'prioritas'       => 'required',
            ],
            [
                'users_id.required'       => 'Pelapor wajib dipilih.',
                'jaringan_id.required'    => 'Data jaringan wajib dipilih.',
                'status_id.required'      => 'Status laporan wajib dipilih.',

                'judul.required'          => 'Judul laporan wajib diisi.',
                'judul.max'               => 'Judul laporan maksimal 100 karakter.',

                'deskripsi.required'      => 'Deskripsi gangguan wajib diisi.',

                'prioritas.required'      => 'Prioritas laporan wajib dipilih.',
            ]
        );

        $users = User::where('id', $request->users_id)->first();

        // ---- PROSES SIMPAN DATA ----
        $jams = Carbon::parse($request->jam)->format('H:i:s');
        $tanggals = Carbon::parse($request->tanggal)->format('Y-m-d');
        $dateTimes = $tanggals . ' ' . $jams;

        LaporanGangguan::where('id', $id)->update([
            'instansi_id' => $users->instansi_id,
            'jaringan_id' => $request->jaringan_id,
            'users_id' => $users->id,
            'status_id' => $request->status_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'waktu_kejadian' => $dateTimes,
            'prioritas' => $request->prioritas,
        ]);
        return redirect()->route('admin-laporangangguan.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data laporan gangguan');
    }

    public function destroy($id)
    {
        LaporanGangguan::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data laporan gangguan',
        ]);
    }
}

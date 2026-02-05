<?php

namespace App\Http\Controllers\Opd;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Models\Jaringan;
use App\Models\LaporanGangguan;
use App\Models\StatusLaporan;
use App\Models\TindakLanjut;
use App\Models\User;
use App\Services\FonteServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpdLaporanGangguanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $users = Auth::user();

            $query = LaporanGangguan::join('instansis', 'laporan_gangguans.instansi_id', 'instansis.id')
                ->join('jaringans', 'laporan_gangguans.jaringan_id', 'jaringans.id')
                ->join('status_laporans', 'laporan_gangguans.status_id', 'status_laporans.id')
                ->join('users', 'laporan_gangguans.users_id', 'users.id')
                ->select([
                    'laporan_gangguans.id',
                    'laporan_gangguans.users_id',
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
                    'status_laporans.nm_status',
                    'status_laporans.warna',
                    'users.name',
                ])
                ->where('laporan_gangguans.users_id', $users->id ?? '')
                ->orderBy('laporan_gangguans.id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('users.name', 'LIKE', "%{$search}%")
                        ->orWhere('instansis.nm_instansi', 'LIKE', "%{$search}%");
                });
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            $dataWithActions = $data->map(function ($item) {

                $resultid = $item->id ?? '';
                $editUrl  = route('opd-laporangangguan.edit', $resultid);
                $showUrl  = route('opd-laporangangguan.show', $resultid);

                // Default button (wajib!)
                $editButton   = '';
                $showButton = '';
                $deleteButton = '';

                // Cek status
                if (($item->status_id ?? '1') === '1') {

                    $editButton = '
            <a href="' . $editUrl . '" class="btn btn-outline-primary">
                <i class="fas fa-edit"></i>
            </a>
        ';

                    $deleteButton = '
            <button type="button"
                    class="btn btn-outline-danger btn-delete"
                    data-resultid="' . e($resultid) . '">
                <i class="fas fa-trash-alt"></i>
            </button>
        ';
                } else {
                    $showButton = '
            <a href="' . $showUrl . '" class="btn btn-outline-primary">
                <i class="fas fa-reply"></i>
            </a>
        ';
                }

                $item->aksi = $editButton . $showButton . $deleteButton;

                return $item;
            });

            return response()->json([
                'draw' => $request->input('draw'), // Ambil nomor draw dari permintaan
                'recordsTotal' => $totalRecords, // Kirim jumlah total data
                'recordsFiltered' => $totalRecords, // Jumlah data yang difilter sama dengan jumlah total
                'data' => $dataWithActions, // Kirim data yang sesuai dengan halaman dan jumlah per halaman
            ]);
        }

        return view('opd.laporan-gangguan.index');
    }

    public function show($id)
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
                'tindak_lanjuts.keterangan',
                'tindak_lanjuts.created_at',
                'tindak_lanjuts.updated_at',
                'status_laporans.nm_status',
                'status_laporans.warna',
                'users.name',
            ])->where('tindak_lanjuts.laporan_id', $id)
            ->orderBy('tindak_lanjuts.id', 'desc')
            ->get();

        return view('opd.laporan-gangguan.show', [
            'laporans' => $laporans,
            'users' => $users,
            'status' => $status,
            'tindakans' => $tindakans,
        ]);
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
        return view('opd.laporan-gangguan.create', [
            'users' => $users,
            'jaringans' => $jaringans,
        ]);
    }

    public function store(Request $request, FonteServices $fonteServices)
    {
        // VALIDASI
        $request->validate([
            'jaringan_id' => 'required',
            'judul'       => 'required|max:100',
            'deskripsi'   => 'required',
            'prioritas'   => 'required',
        ], [
            'jaringan_id.required' => 'Data jaringan wajib dipilih.',
            'judul.required'       => 'Judul laporan wajib diisi.',
            'judul.max'            => 'Judul laporan maksimal 100 karakter.',
            'deskripsi.required'   => 'Deskripsi gangguan wajib diisi.',
            'prioritas.required'   => 'Prioritas laporan wajib dipilih.',
        ]);

        $user  = Auth::user();
        $instansis = Instansi::where('id', $user->instansi_id)->first();
        $nmInstansi = $instansis->nm_instansi ?? 'Kominfo';
        $telp = $instansis->telp ?? '6281372924746';
        $adminPhone = '628136550532';
        $token = $fonteServices->getToken();   // TOKEN FONNTE
        $target = ''. $telp .'|Lily|Client,'. $adminPhone .'|Fonnte|Admin,';

        // WAKTU KEJADIAN
        $waktuKejadian = Carbon::parse(
            $request->tanggal . ' ' . $request->jam
        )->format('Y-m-d H:i:s');

        // SIMPAN DATA
        $laporan = LaporanGangguan::create([
            'instansi_id'    => $user->instansi_id,
            'jaringan_id'    => $request->jaringan_id,
            'users_id'       => $user->id,
            'status_id'      => 1,
            'judul'          => $request->judul,
            'deskripsi'      => $request->deskripsi,
            'waktu_kejadian' => $waktuKejadian,
            'prioritas'      => $request->prioritas,
        ]);

        // PESAN WHATSAPP
        $message =
            "📡 *LAPORAN GANGGUAN JARINGAN*\n\n" .
            "*Instansi:* {$nmInstansi}\n" .
            "*Judul:* {$laporan->judul}\n" .
            "*Prioritas:* {$laporan->prioritas}\n" .
            "*Pelapor:* {$user->name}\n" .
            "*Waktu:* " . Carbon::parse($waktuKejadian)->format('d-m-Y H:i') . "\n\n" .
            "*Deskripsi:*\n{$laporan->deskripsi}";

        // KIRIM KE FONNTE
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'target'      => $target,
                'message'     => $message,
                'delay'       => '2',
                'countryCode' => '62',
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $token,
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl) || $httpCode !== 200) {
            $error = curl_error($curl);
            curl_close($curl);

            return back()->withErrors(
                'Gagal mengirim WhatsApp. ' . ($error ?: 'HTTP Code: ' . $httpCode)
            );
        }

        curl_close($curl);

        return redirect()
            ->route('opd-laporangangguan.index')
            ->with('success', 'Laporan berhasil disimpan & notifikasi WhatsApp terkirim.');
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
        $laporans = LaporanGangguan::where('id', $id)->first();
        return view('opd.laporan-gangguan.edit', [
            'users' => $users,
            'jaringans' => $jaringans,
            'laporans' => $laporans,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'jaringan_id'     => 'required',
                'judul'           => 'required|max:100',
                'deskripsi'       => 'required',
                'prioritas'       => 'required',
            ],
            [
                'jaringan_id.required'    => 'Data jaringan wajib dipilih.',

                'judul.required'          => 'Judul laporan wajib diisi.',
                'judul.max'               => 'Judul laporan maksimal 100 karakter.',

                'deskripsi.required'      => 'Deskripsi gangguan wajib diisi.',

                'prioritas.required'      => 'Prioritas laporan wajib dipilih.',
            ]
        );

        $auth = Auth::user();
        $users = User::where('id', $auth->id)->first();

        // ---- PROSES SIMPAN DATA ----
        $jams = Carbon::parse($request->jam)->format('H:i:s');
        $tanggals = Carbon::parse($request->tanggal)->format('Y-m-d');
        $dateTimes = $tanggals . ' ' . $jams;

        LaporanGangguan::where('id', $id)->update([
            'instansi_id' => $users->instansi_id,
            'jaringan_id' => $request->jaringan_id,
            'users_id' => $users->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'waktu_kejadian' => $dateTimes,
            'prioritas' => $request->prioritas,
        ]);
        return redirect()->route('opd-laporangangguan.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data laporan gangguan');
    }

    public function destroy($id)
    {
        LaporanGangguan::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data laporan gangguan',
        ]);
    }

    public function services(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'target' => '628136550532',
                'message' => 'test message',
                'countryCode' => '62',
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: 35qCv3v8ahWddXbJpZkA',
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return response()->json([
                'error' => curl_error($curl)
            ], 500);
        }

        curl_close($curl);

        return $response;
    }
}

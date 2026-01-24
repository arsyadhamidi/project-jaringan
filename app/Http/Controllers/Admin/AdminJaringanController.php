<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Models\Jaringan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminJaringanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Jaringan::join('instansis', 'jaringans.instansi_id', 'instansis.id')
                ->select([
                    'jaringans.id',
                    'jaringans.instansi_id',
                    'jaringans.tipe_jaringan',
                    'jaringans.provider',
                    'jaringans.ip_address',
                    'jaringans.bandwidth',
                    'jaringans.status',
                    'jaringans.keterangan',
                    'instansis.nm_instansi',
                ])
                ->orderBy('jaringans.id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('instansis.nm_instansi', 'LIKE', "%{$search}%")
                        ->orWhere('jaringans.provider', 'LIKE', "%{$search}%")
                        ->orWhere('jaringans.ip_address', 'LIKE', "%{$search}%")
                        ->orWhere('jaringans.bandwidth', 'LIKE', "%{$search}%")
                        ->orWhere('jaringans.status', 'LIKE', "%{$search}%")
                        ->orWhere('jaringans.tipe_jaringan', 'LIKE', "%{$search}%");
                });
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-jaringan.edit', $item->id ?? '');

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

        return view('admin.jaringan.index');
    }

    public function create()
    {
        $instansis = Instansi::get();
        return view('admin.jaringan.create', [
            'instansis' => $instansis,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'instansi_id'  => 'required',
                'tipe_jaringan' => 'required|max:100',
                'provider'     => 'required|max:100',
                'ip_address'   => 'required|max:100',
                'bandwidth'    => 'required|max:100',
                'keterangan'   => 'required|max:100',
            ],
            [
                'instansi_id.required'  => 'Instansi wajib dipilih.',

                'tipe_jaringan.required' => 'Tipe jaringan wajib diisi.',
                'tipe_jaringan.max'      => 'Tipe jaringan maksimal 100 karakter.',

                'provider.required'     => 'Provider jaringan wajib diisi.',
                'provider.max'          => 'Provider jaringan maksimal 100 karakter.',

                'ip_address.required'   => 'IP Address wajib diisi.',
                'ip_address.max'        => 'IP Address maksimal 100 karakter.',

                'bandwidth.required'    => 'Bandwidth wajib diisi.',
                'bandwidth.max'         => 'Bandwidth maksimal 100 karakter.',

                'keterangan.required'   => 'Keterangan wajib diisi.',
                'keterangan.max'        => 'Keterangan maksimal 100 karakter.',
            ]
        );


        Jaringan::create([
            'instansi_id' => $request->instansi_id,
            'tipe_jaringan' => $request->tipe_jaringan,
            'provider' => $request->provider,
            'ip_address' => $request->ip_address,
            'bandwidth' => $request->bandwidth,
            'status' => 'Online',
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin-jaringan.index')->with('success', 'Selamat ! Anda berhasil menambahkan data jaringan');
    }

    public function edit($id)
    {
        $jaringans = Jaringan::where('id', $id)->first();
        $instansis = Instansi::get();
        return view('admin.jaringan.edit', [
            'jaringans' => $jaringans,
            'instansis' => $instansis,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'instansi_id'  => 'required',
                'tipe_jaringan' => 'required|max:100',
                'provider'     => 'required|max:100',
                'ip_address'   => 'required|max:100',
                'bandwidth'    => 'required|max:100',
                'keterangan'   => 'required|max:100',
            ],
            [
                'instansi_id.required'  => 'Instansi wajib dipilih.',

                'tipe_jaringan.required' => 'Tipe jaringan wajib diisi.',
                'tipe_jaringan.max'      => 'Tipe jaringan maksimal 100 karakter.',

                'provider.required'     => 'Provider jaringan wajib diisi.',
                'provider.max'          => 'Provider jaringan maksimal 100 karakter.',

                'ip_address.required'   => 'IP Address wajib diisi.',
                'ip_address.max'        => 'IP Address maksimal 100 karakter.',

                'bandwidth.required'    => 'Bandwidth wajib diisi.',
                'bandwidth.max'         => 'Bandwidth maksimal 100 karakter.',

                'keterangan.required'   => 'Keterangan wajib diisi.',
                'keterangan.max'        => 'Keterangan maksimal 100 karakter.',
            ]
        );


        Jaringan::where('id', $id)->update([
            'instansi_id' => $request->instansi_id,
            'tipe_jaringan' => $request->tipe_jaringan,
            'provider' => $request->provider,
            'ip_address' => $request->ip_address,
            'bandwidth' => $request->bandwidth,
            'status' => 'Online',
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin-jaringan.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data jaringan');
    }

    public function destroy($id)
    {
        Jaringan::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data jaringan',
        ]);
    }

    public function ping(Request $request)
    {
        $request->validate([
            'ip' => 'required|ip'
        ]);

        $ip = escapeshellarg($request->ip);

        exec("ping -n 1 -w 1000 $ip", $output, $status);

        return response()->json([
            'status' => $status === 0 ? 'Online' : 'Offline'
        ]);
    }
}

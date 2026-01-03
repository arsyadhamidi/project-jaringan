<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatusLaporan;
use Illuminate\Http\Request;

class AdminStatusLaporanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = StatusLaporan::orderBy('id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('nm_status', 'LIKE', "%{$search}%")
                        ->orWhere('warna', 'LIKE', "%{$search}%");
                });
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-status.edit', $item->id ?? '');

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

        return view('admin.status-laporan.index');
    }

    public function create()
    {
        return view('admin.status-laporan.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'nm_status'   => 'required',
                'warna'   => 'required',
            ],
            [
                'nm_status.required'   => 'Nama Status wajib diisi.',

                'warna.required'   => 'Warna wajib diisi.',
            ]
        );

        StatusLaporan::create([
            'nm_status' => $request->nm_status,
            'warna' => $request->warna,
        ]);

        return redirect()->route('admin-status.index')->with('success', 'Selamat ! Anda berhasil menambahkan data status laporan');
    }

    public function edit($id)
    {
        $status = StatusLaporan::where('id', $id)->first();
        return view('admin.status-laporan.edit', [
            'status' => $status,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'nm_status'   => 'required',
                'warna'   => 'required',
            ],
            [
                'nm_status.required'   => 'Nama Status wajib diisi.',

                'warna.required'   => 'Warna wajib diisi.',
            ]
        );

        StatusLaporan::where('id', $id)->update([
            'nm_status' => $request->nm_status,
            'warna' => $request->warna,
        ]);

        return redirect()->route('admin-status.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data status laporan');
    }

    public function destroy($id)
    {
        StatusLaporan::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data status laporan',
        ]);
    }
}

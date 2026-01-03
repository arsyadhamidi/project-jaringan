<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;

class AdminLevelController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Level::orderBy('id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('nm_level', 'LIKE', "%{$search}%")
                        ->orWhere('keterangan', 'LIKE', "%{$search}%");
                });
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-level.edit', $item->id ?? '');

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

        return view('admin.level.index');
    }

    public function create()
    {
        return view('admin.level.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'id_level'   => 'required|max:5',
                'nm_level'   => 'required|max:100',
                'keterangan' => 'required|max:100',
            ],
            [
                'id_level.required'   => 'ID Level wajib diisi.',
                'id_level.max'        => 'ID Level maksimal 5 karakter.',

                'nm_level.required'   => 'Nama Level wajib diisi.',
                'nm_level.max'        => 'Nama Level maksimal 100 karakter.',

                'keterangan.required' => 'Keterangan wajib diisi.',
                'keterangan.max'      => 'Keterangan maksimal 100 karakter.',
            ]
        );

        Level::create([
            'id_level' => $request->id_level,
            'nm_level' => $request->nm_level,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin-level.index')->with('success', 'Selamat ! Anda berhasil menambahkan data level');
    }

    public function edit($id)
    {
        $levels = Level::where('id', $id)->first();
        return view('admin.level.edit', [
            'levels' => $levels,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'id_level'   => 'required|max:5',
                'nm_level'   => 'required|max:100',
                'keterangan' => 'required|max:100',
            ],
            [
                'id_level.required'   => 'ID Level wajib diisi.',
                'id_level.max'        => 'ID Level maksimal 5 karakter.',

                'nm_level.required'   => 'Nama Level wajib diisi.',
                'nm_level.max'        => 'Nama Level maksimal 100 karakter.',

                'keterangan.required' => 'Keterangan wajib diisi.',
                'keterangan.max'      => 'Keterangan maksimal 100 karakter.',
            ]
        );

        Level::where('id', $id)->update([
            'id_level' => $request->id_level,
            'nm_level' => $request->nm_level,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin-level.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data level');
    }

    public function destroy($id)
    {
        Level::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data level',
        ]);
    }
}

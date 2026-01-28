<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use Illuminate\Http\Request;
use PDF;

class AdminInstansiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Instansi::orderBy('id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('nm_instansi', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('telp', 'LIKE', "%{$search}%")
                        ->orWhere('alamat', 'LIKE', "%{$search}%");
                });
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-instansi.edit', $item->id ?? '');

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

        return view('admin.instansi.index');
    }

    public function create()
    {
        return view('admin.instansi.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'nm_instansi' => 'required|max:100',
                'email'      => 'required|max:255',
                'telp'       => 'required|max:20',
                'alamat'     => 'required',
            ],
            [
                'nm_instansi.required' => 'Nama instansi wajib diisi.',
                'nm_instansi.max'      => 'Nama instansi maksimal 100 karakter.',

                'email.required'       => 'Email instansi wajib diisi.',
                'email.max'            => 'Email maksimal 255 karakter.',

                'telp.required'        => 'Nomor telepon wajib diisi.',
                'telp.max'             => 'Nomor telepon maksimal 20 karakter.',

                'alamat.required'      => 'Alamat instansi wajib diisi.',
            ]
        );

        Instansi::create([
            'nm_instansi' => $request->nm_instansi,
            'email' => $request->email,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin-instansi.index')->with('success', 'Selamat ! Anda berhasil menambahkan data instansi');
    }

    public function edit($id)
    {
        $instansis = Instansi::where('id', $id)->first();
        return view('admin.instansi.edit', [
            'instansis' => $instansis,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'nm_instansi' => 'required|max:100',
                'email'      => 'required|max:255',
                'telp'       => 'required|max:20',
                'alamat'     => 'required',
            ],
            [
                'nm_instansi.required' => 'Nama instansi wajib diisi.',
                'nm_instansi.max'      => 'Nama instansi maksimal 100 karakter.',

                'email.required'       => 'Email instansi wajib diisi.',
                'email.max'            => 'Email maksimal 255 karakter.',

                'telp.required'        => 'Nomor telepon wajib diisi.',
                'telp.max'             => 'Nomor telepon maksimal 20 karakter.',

                'alamat.required'      => 'Alamat instansi wajib diisi.',
            ]
        );

        Instansi::where('id', $id)->update([
            'nm_instansi' => $request->nm_instansi,
            'email' => $request->email,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin-instansi.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data instansi');
    }

    public function destroy($id)
    {
        Instansi::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data instansi',
        ]);
    }

    public function generatepdf()
    {
        $instansis = Instansi::get();

        $pdf = PDF::loadview('admin.instansi.export-pdf', [
            'instansis' => $instansis
        ])->setPaper('A4', 'Potrait');;
        // return $pdf->download('laporan-gangguan.pdf');
        return $pdf->stream('instansi.pdf');
    }
}

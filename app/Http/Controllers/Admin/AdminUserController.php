<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = User::join('instansis', 'users.instansi_id', 'instansis.id')
                ->join('levels', 'users.level_id', 'levels.id_level')
                ->select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.password',
                    'users.duplicate',
                    'users.telp',
                    'users.status',
                    'instansis.nm_instansi',
                    'levels.nm_level',
                ])->orderBy('users.id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('users.name', 'LIKE', "%{$search}%")
                        ->orWhere('levels.nm_level', 'LIKE', "%{$search}%")
                        ->orWhere('instansis.nm_instansi', 'LIKE', "%{$search}%");
                });
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-users.edit', $item->id ?? '');

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

        return view('admin.user.index');
    }

    public function create()
    {
        $levels = Level::get();
        $instansis = Instansi::get();
        return view('admin.user.create', [
            'levels' => $levels,
            'instansis' => $instansis,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'instansi_id' => 'required',
                'level_id'    => 'required',
                'name'        => 'required|max:255',
                'email'       => 'required|unique:users,email|max:255',
                'telp'        => 'required|max:20',
            ],
            [
                'instansi_id.required' => 'Instansi wajib dipilih.',
                'level_id.required'    => 'Level pengguna wajib dipilih.',

                'name.required'        => 'Nama pengguna wajib diisi.',
                'name.max'             => 'Nama pengguna maksimal 255 karakter.',

                'email.required'       => 'Email pengguna wajib diisi.',
                'email.unique'         => 'Email sudah digunakan.',
                'email.max'            => 'Email maksimal 255 karakter.',

                'telp.required'        => 'Nomor telepon wajib diisi.',
                'telp.max'             => 'Nomor telepon maksimal 20 karakter.',
            ]
        );

        User::create([
            'instansi_id' => $request->instansi_id,
            'level_id' => $request->level_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('12345678'),
            'duplicate' => '12345678',
            'telp' => $request->telp,
            'status' => '1',
        ]);

        return redirect()->route('admin-users.index')->with('success', 'Selamat ! Anda berhasil menambahkan data user registrasi');
    }

    public function edit($id)
    {
        $users = User::where('id', $id)->first();
        $levels = Level::get();
        $instansis = Instansi::get();
        return view('admin.user.edit', [
            'users' => $users,
            'levels' => $levels,
            'instansis' => $instansis,
        ]);
    }

    public function update(Request $request, $id)
    {
       $request->validate(
            [
                'instansi_id' => 'required',
                'level_id'    => 'required',
                'name'        => 'required|max:255',
                'email'       => 'required|unique:users,email|max:255',
                'telp'        => 'required|max:20',
            ],
            [
                'instansi_id.required' => 'Instansi wajib dipilih.',
                'level_id.required'    => 'Level pengguna wajib dipilih.',

                'name.required'        => 'Nama pengguna wajib diisi.',
                'name.max'             => 'Nama pengguna maksimal 255 karakter.',

                'email.required'       => 'Email pengguna wajib diisi.',
                'email.unique'         => 'Email sudah digunakan.',
                'email.max'            => 'Email maksimal 255 karakter.',

                'telp.required'        => 'Nomor telepon wajib diisi.',
                'telp.max'             => 'Nomor telepon maksimal 20 karakter.',
            ]
        );

        User::where('id', $id)->update([
            'instansi_id' => $request->instansi_id,
            'level_id' => $request->level_id,
            'name' => $request->name,
            'email' => $request->email,
            'telp' => $request->telp,
        ]);

        return redirect()->route('admin-users.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data registrasi');
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data user registrasi',
        ]);
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Instansi;
use App\Models\Level;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Instansi::create([
            'nm_instansi' => 'Kominfo Payakumbuh',
            'email' => 'admin@payakumbuhkota.go.id',
            'telp' => '+62 752 93279',
            'alamat' => 'Sektetariat Daerah Komplek Perkantoran Balaikota Baru Jl. Veteran No 70 , Kel. Kapalo Koto Dibalai, Kec. Payakumbuh Utara Kota Payakumbuh'
        ]);

        Level::create([
            'id_level' => '1',
            'nm_level' => 'Super Admin',
            'keterangan' => '-',
        ]);

        User::create([
            'instansi_id' => '1',
            'level_id' => '1',
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'duplicate' => '12345678',
            'telp' => '081372924746',
            'status' => '1'
        ]);
    }
}

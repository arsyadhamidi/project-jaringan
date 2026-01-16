<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanGangguanExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // Map data to include country name
        return $this->data->map(function ($item) {
            return [
                'id' => $item->id,
                'instansi_id' => $item->nm_instansi,
                'tipe_jaringan' => $item->tipe_jaringan,
                'provider' => $item->provider,
                'ip_address' => $item->ip_address,
                'bandwidth' => $item->bandwidth,
                'status' => $item->status,
                'keterangan' => $item->keterangan,
                'name' => $item->name,
                'email' => $item->email,
                'telp' => $item->telp,
                'judul' => $item->judul,
                'deskripsi' => $item->deskripsi,
                'waktu_kejadian' => $item->waktu_kejadian,
                'prioritas' => $item->prioritas,
                'nm_status' => $item->nm_status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Instansi',
            'Tipe Jaringan',
            'Provider',
            'Ip Address',
            'Bandwidth',
            'Status',
            'Keterangan',
            'Nama Lengkap',
            'Email',
            'Telepon',
            'Judul',
            'Deskripsi',
            'Waktu Kejadian',
            'Prioritas',
            'Status',
        ];
    }
}

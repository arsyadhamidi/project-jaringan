<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jaringan;
use Illuminate\Support\Facades\Log;

class CheckJaringan extends Command
{
    protected $signature = 'jaringan:check';
    protected $description = 'Cek status jaringan otomatis (ONLINE / OFFLINE)';

    public function handle()
    {
        Log::info('=== Mulai cek jaringan ===', [
            'waktu' => now()->toDateTimeString()
        ]);

        $jaringans = Jaringan::all();

        foreach ($jaringans as $jaringan) {

            $cmd = "ping -n 2 -w 1000 {$jaringan->ip_address}";
            exec($cmd . " 2>&1", $output, $status);

            if ($status === 0) {
                $jaringan->update([
                    'status' => 'Online',
                ]);

                Log::info('Jaringan ONLINE', [
                    'ip' => $jaringan->ip_address,
                    'instansi_id' => $jaringan->instansi_id,
                    'waktu' => now()->toDateTimeString()
                ]);

                $this->info("{$jaringan->ip_address} => ONLINE");
            } else {
                $jaringan->update([
                    'status' => 'Offline',
                ]);

                Log::error('Jaringan OFFLINE', [
                    'ip' => $jaringan->ip_address,
                    'instansi_id' => $jaringan->instansi_id,
                    'waktu' => now()->toDateTimeString()
                ]);

                $this->error("{$jaringan->ip_address} => OFFLINE");
            }
        }

        Log::info('=== Selesai cek jaringan ===');

        return Command::SUCCESS;
    }
}

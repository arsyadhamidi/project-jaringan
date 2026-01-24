<?php

namespace App\Jobs;

use App\Models\Jaringan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PingJaringanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $data = Jaringan::all();

        foreach ($data as $jaringan) {
            $ip = escapeshellarg($jaringan->ip_address);

            // Windows
            exec("ping -n 1 -w 1000 $ip", $output, $status);

            $jaringan->update([
                'status'     => $status === 0 ? 'Online' : 'Offline',
            ]);
        }
    }
}

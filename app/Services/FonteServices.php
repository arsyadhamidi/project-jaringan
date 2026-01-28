<?php

namespace App\Services;

class FonnteService
{
    protected string $token;
    protected string $target;

    public function __construct()
    {
        $this->token  = "35qCv3v8ahWddXbJpZkA";
        $this->target = "628136550532";
    }

    public function send(string $message)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'target'      => $this->target,
                'message'     => $message,
                'countryCode' => '62',
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $this->token,
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}

<?php

namespace App\Services;

class FonteServices
{
    protected $target;
    protected $token;

    public function __construct()
    {
        $this->target       = "628136550532";
        $this->token        = "35qCv3v8ahWddXbJpZkA";
    }

    /**
     * Ambil semua konfigurasi dalam bentuk array
     */
    public function all(): array
    {
        return [
            'target'        => $this->target,
            'token'    => $this->token,
        ];
    }

    /**
     * Getter per item kalau mau dipanggil satuan
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}

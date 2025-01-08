<?php

namespace App\Services;

class JWTService
{
    protected $decodedToken;

    public function setDecodedToken($token)
    {
        $this->decodedToken = $token;
    }

    public function getDecodedToken()
    {
        return $this->decodedToken;
    }
}
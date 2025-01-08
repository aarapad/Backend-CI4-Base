<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use App\Services\JWTService;

class Services extends BaseService
{
    public static function jwt(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('jwt');
        }

        return new JWTService();
    }
}
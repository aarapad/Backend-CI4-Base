<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public $aliases = [
        'jwt'  => \App\Filters\JWTAuthFilter::class
    ];

    public $globals = [
        'before' => [
        ],
        'after' => []
    ];
}
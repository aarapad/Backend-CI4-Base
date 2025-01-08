<?php

if (!function_exists('getJwtSecret')) {
    function getJwtSecret() {
        return getenv('JWT_SECRET') ?: 'ixK#9mP$vL2nQ&wR5hJ@8cF4tY6gN!3sB7kM*pZ9dX%vA2jE5uH8nW4qL';
    }
}

if (!function_exists('validateJwtToken')) {
    function validateJwtToken(?string $token) {
        if (empty($token)) {
            return null;
        }
        
        try {
            return \Firebase\JWT\JWT::decode(
                $token,
                new \Firebase\JWT\Key(getJwtSecret(), 'HS256')
            );
        } catch (\Exception $e) {
            log_message('error', 'JWT validation error: ' . $e->getMessage());
            return null;
        }
    }
}
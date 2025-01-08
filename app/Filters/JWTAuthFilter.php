<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JWTAuthFilter implements FilterInterface
{
    public function __construct()
    {
        // Cargar el helper JWT
        helper('jwt');
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        if ($request->getServer('REQUEST_METHOD') === 'OPTIONS') {
            return $request;
        }

        try {
            log_message('error', '====== INICIO JWT FILTER ======');

            // Obtener headers directamente
            $headers = apache_request_headers();
            log_message('error', 'Headers recibidos: ' . json_encode($headers));

            // Buscar el header de autorización (case-insensitive)
            $authHeader = null;
            foreach ($headers as $key => $value) {
                if (strtolower($key) === 'authorization') {
                    $authHeader = $value;
                    break;
                }
            }

            if (!$authHeader) {
                log_message('error', 'No se encontró header de autorización');
                return Services::response()
                    ->setJSON(['error' => 'Token no proporcionado'])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }

            // Extraer el token
            $token = trim(str_replace('Bearer', '', $authHeader));
            log_message('error', 'Token extraído: ' . $token);

            // Decodificar token
            $decoded = JWT::decode($token, new Key(getJwtSecret(), 'HS256'));
            log_message('error', 'Token decodificado: ' . json_encode($decoded));

            // Guardar en el servicio
            $jwtService = Services::jwt();
            $jwtService->setDecodedToken($decoded);

            log_message('error', '====== FIN JWT FILTER ======');
            return $request;

        } catch (Exception $e) {
            log_message('error', 'Error validando token JWT: ' . $e->getMessage());
            return Services::response()
                ->setJSON(['error' => 'Token inválido o expirado'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
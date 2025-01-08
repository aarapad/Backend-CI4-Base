<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Firebase\JWT\JWT;

class AuthController extends BaseController
{
    use ResponseTrait;

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        try {
            log_message('error', '====== INICIO LOGIN ======');
            
            // Obtener datos del form
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            
            log_message('error', 'Email recibido: ' . $email);

            // Validar datos
            if (!$email || !$password) {
                log_message('error', 'Datos incompletos');
                return $this->fail('Datos de acceso incompletos', 400);
            }

            // Buscar usuario
            $user = $this->userModel->findUserByEmail($email);
            if (!$user) {
                log_message('error', 'Usuario no encontrado');
                return $this->fail('Credenciales inválidas', 401);
            }

            // Verificar contraseña
            if (!password_verify($password, $user['password'])) {
                log_message('error', 'Contraseña incorrecta');
                return $this->fail('Credenciales inválidas', 401);
            }

            // Generar token
            $token = $this->generateToken($user);
            
            $response = [
                'token' => $token,
                'user' => $this->filterUserData($user)
            ];

            log_message('error', 'Login exitoso para: ' . $email);
            log_message('error', '====== FIN LOGIN ======');

            return $this->respond($response);

        } catch (\Exception $e) {
            log_message('error', 'Error en login: ' . $e->getMessage());
            return $this->fail('Error interno del servidor', 500);
        }
    }

    public function logout()
    {
        try {
            // Obtener el token decodificado del servicio
            $jwtService = service('jwt');
            $decoded = $jwtService->getDecodedToken();
            
            log_message('error', 'Usuario ' . $decoded->email . ' ha cerrado sesión');
            
            return $this->respond([
                'message' => 'Sesión cerrada correctamente'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en logout: ' . $e->getMessage());
            return $this->fail('Error al cerrar sesión', 500);
        }
    }

    private function generateToken(array $userData): string
    {
        $payload = [
            'iss' => 'backend-factur@',
            'aud' => 'vue-frontend',
            'iat' => time(),
            'exp' => time() + (365 * 24 * 60 * 60), // 1 año
            'user_id' => $userData['id'],
            'email' => $userData['email'],
            'role' => $userData['role']
        ];

        return JWT::encode($payload, getJwtSecret(), 'HS256');
    }

    private function filterUserData(array $user): array
    {
        return [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'role' => $user['role']
        ];
    }
}
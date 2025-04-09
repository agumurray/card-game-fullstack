<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repositories\UsuarioRepository;
use Firebase\JWT\JWT;

class AuthController
{
    private $secretKey;

    public function __construct(private UsuarioRepository $repo)
    {
        $this->secretKey = getenv('JWT_SECRET');
    }

    public function register(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $error = $this->repo->validarRegistro($data);
        if ($error) {
            return $this->withJson($response, ['error' => $error], 400);
        }

        $success = $this->repo->crearUsuario($data);

        if ($success) {
            return $this->withJson($response, ['status' => 'usuario creado']);
        }

        return $this->withJson($response, ['error' => 'No se pudo crear el usuario'], 400);
    }

    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $usuario = $this->repo->buscarPorUsuario($data['usuario'] ?? '');

        if (!$usuario || !password_verify($data['clave'] ?? '', $usuario['password'])) {
            return $this->withJson($response, ['status' => 'error', 'message' => 'Credenciales inválidas'], 401);
        }

        $payload = [
            'sub' => $usuario['id'],
            'name' => $usuario['nombre'],
            'iat' => time(),
            'exp' => time() + 3600,
        ];

        $token = JWT::encode($payload, $this->secretKey, 'HS256');
        $token = substr($token, 0, 128);

        $this->repo->guardarToken($usuario['id'], $token, $payload['exp']);

        return $this->withJson($response, [
            'status' => 'success',
            'token' => $token
        ]);
    }

    private function withJson(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }
}

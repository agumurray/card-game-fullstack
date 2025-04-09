<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repositories\UsuarioRepository;

class UsuarioController
{

    public function __construct(private UsuarioRepository $repo)
    {
    }

    public function actualizar(Request $request, Response $response, array $args): Response
    {
        $id_usuario = $args['usuario'];
        $data = json_decode($request->getBody()->getContents(), true);

        if (!$this->repo->tokenValido($id_usuario, $data['token'] ?? '')) {
            return $this->withJson($response, ['status' => 'error', 'message' => 'Token inválido o expirado'], 401);
        }

        $success = $this->repo->actualizarUsuario($id_usuario, $data['nombre'], $data['contrasenia']);

        if ($success) {
            return $this->withJson($response, ['mensaje' => 'Usuario actualizado correctamente']);
        }

        return $this->withJson($response, ['error' => 'No se pudo actualizar el usuario'], 500);
    }

    private function withJson(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }
}

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

        $success = $this->repo->actualizarUsuario($id_usuario, $data['nombre'], $data['clave']);

        if ($success) {
            return $this->withJson($response, ['mensaje' => 'Usuario actualizado correctamente']);
        }

        return $this->withJson($response, ['error' => 'No se pudo actualizar el usuario'], 500);
    }

    public function obtener(Request $request, Response $response, array $args):Response
    {
        $id_usuario=$args['usuario'];
        //busco usuario
        $usuario=$this->repo->buscarPorId($id_usuario);
        return $this->withJson($response,['usuario'=>$usuario]);
    }

    private function withJson(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }
}

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
        $id_usuario =(int) $args['usuario'];
        $data = json_decode($request->getBody()->getContents(), true);
        $nombre = $data['nombre'] ?? '';

        if (!$nombre) {
            return $this->withJson($response, ['error' => 'Debe enviarse un nombre de usuario'], 400);
        }

        $success = $this->repo->actualizarUsuario($id_usuario, $nombre, $data['clave']);

        if ($success) {
            return $this->withJson($response, ['mensaje' => 'Usuario actualizado correctamente']);
        }

        return $this->withJson($response, ['error' => 'No se pudo actualizar el usuario'], 400);
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

<?php

namespace App\Controllers;

use App\Repositories\MazoCartaRepository;
use App\Repositories\UsuarioRepository;
use App\Repositories\CartaRepository;
use App\Repositories\MazoRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MazoController
{
    public function __construct(private MazoRepository $repo_mazo, private UsuarioRepository $repo_usuario, private CartaRepository $repo_cartas, private MazoCartaRepository $repo_mazo_carta)
    {
    }

    public function agregar(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $cartas = $data['cartas'] ?? '';
        $nombre_mazo = $data['nombre'] ?? '';
        $id = $request->getAttribute('id_usuario');

        if (!is_array($cartas) || count($cartas) === 0 || count($cartas) > 5 || empty($nombre_mazo) || (count($cartas) !== count(array_unique($cartas)))) {
            return $this->withJson($response, [
                'status' => 'error',
                'message' => 'Debe enviarse un nombre de mazo y un array de entre 1 y 5 IDs de cartas unicos'
            ], 400);
        }
        
        
        if(!$this->repo_cartas->validarCartas($cartas)){
            return $this->withJson($response, ['status' => 'error', 'message' => 'Una o mas cartas no existen en la base de datos'], 401);
        }

        $id_mazo = $this->repo_mazo->crearMazo($id,$nombre_mazo);
        if (!$id_mazo || !$this->repo_mazo_carta->crearMazo($cartas,$id_mazo)){
            return $this->withJson($response, ['status'=>'error', 'message' => 'Error al insertar el mazo'], 401);
        }


        return $this->withJson($response, [
            'status' => 'success',
            'id mazo' => $id_mazo,
            'nombre mazo' => $nombre_mazo
        ]);
    }

    public function buscarCartasFiltro(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $atributo = $params['atributo'] ?? null;
        $nombre = $params['nombre'] ?? '';
    
        if (empty($atributo)) {
            return $this->withJson($response, [
                'error' => 'El parámetro "atributo" es obligatorio.'
            ], 400);
        }
    
        $cartas = $this->repo_cartas->buscarCartasPorAtributoYNombre($atributo, $nombre);
        if (empty($cartas)) {
            return $this->withJson($response, [
                'mensaje' => 'No se encontraron cartas que coincidan con los filtros proporcionados.'
            ], 404);
        }
        
        return $this->withJson($response, $cartas);
    }

    private function withJson(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }
}
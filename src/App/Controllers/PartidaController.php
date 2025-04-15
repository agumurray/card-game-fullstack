<?php

namespace App\Controllers;

use App\Repositories\MazoCartaRepository;
use App\Repositories\CartaRepository;
use App\Repositories\UsuarioRepository;
use App\Repositories\PartidaRepository;
use App\Repositories\MazoRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PartidaController
{
    public function __construct(private CartaRepository $repo_carta, private MazoRepository $repo_mazo, private UsuarioRepository $repo_usuario, private PartidaRepository $repo_partida, private MazoCartaRepository $repo_mazo_carta)
    {
    }

    public function crearPartida(Request $request, Response $response): Response
    {

        $data = json_decode($request->getBody()->getContents(), true);
        $id_usuario = $request->getAttribute('id_usuario');
        $id_mazo = $data['id_mazo'] ?? '';

        if (!$this->repo_mazo->validarMazo($id_usuario,$id_mazo)) {
            return $this->withJson($response, ['error' => 'este mazo no pertence al usuario logueado'], 401);
        }
        $id_partida = $this->repo_partida->crearPartida($id_usuario,$id_mazo);
        $cartas = $this->repo_mazo_carta->actualizarCartas($id_mazo);
        $datocarta = $this->repo_mazo_carta->buscarIdCartas($id_mazo);
        if ($id_partida && $cartas){
            $descarta=$this->repo_carta->mostrarCartas($datocarta);
            return $this->withJson($response, ['mensaje' => 'Partida creada correctamente','id de partida'=> $id_partida,'cartas'=> $descarta]);
        }

        return $this->withJson($response, ['error' => 'No se pudo crear la partida'], 500);
    }

    private function withJson(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }
    
}
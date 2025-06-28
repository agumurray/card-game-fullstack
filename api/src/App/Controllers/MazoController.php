<?php

namespace App\Controllers;

use App\Repositories\MazoCartaRepository;
use App\Repositories\AtributoRepository;
use App\Repositories\PartidaRepository;
use App\Repositories\UsuarioRepository;
use App\Repositories\CartaRepository;
use App\Repositories\MazoRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MazoController
{
    public function __construct(private PartidaRepository $repo_partida, private MazoRepository $repo_mazo, private UsuarioRepository $repo_usuario, private CartaRepository $repo_cartas, private MazoCartaRepository $repo_mazo_carta, private AtributoRepository $repo_atributo)
    {
    }

    public function agregar(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $cartas = $data['cartas'] ?? '';
        $nombre_mazo = $data['nombre'] ?? '';
        $id = $request->getAttribute('id_usuario');

        if (!is_array($cartas) || count($cartas) === 0 || count($cartas) != 5 || empty($nombre_mazo) || (count($cartas) !== count(array_unique($cartas)))) {
            return $this->withJson($response, [
                'status' => 'error',
                'message' => 'Debe enviarse un nombre de mazo y un array de 5 IDs de cartas unicos'
            ], 400);
        }


        if (!$this->repo_cartas->validarCartas($cartas)) {
            return $this->withJson($response, ['status' => 'error', 'message' => 'Una o mas cartas no existen en la base de datos'], 404);
        }

        $id_mazo = $this->repo_mazo->crearMazo($id, $nombre_mazo);
        if (!$id_mazo || !$this->repo_mazo_carta->crearMazo($cartas, $id_mazo)) {
            return $this->withJson($response, ['status' => 'error', 'message' => 'Error al insertar el mazo'], 400);
        }


        return $this->withJson($response, [
            'status' => 'success',
            'id mazo' => $id_mazo,
            'nombre mazo' => $nombre_mazo
        ]);
    }

    public function actualizarMazo(Request $request, Response $response, array $args): Response
    {
        $id_mazo = (int) $args['mazo'];
        $data = $request->getParsedBody();
        $nombre_mazo = $data['nombre'] ?? "";
        $id_usuario = $request->getAttribute('id_usuario');

        if (empty($nombre_mazo)) {
            return $this->withJson($response, ['error' => 'Debe enviar un nombre de mazo'], 400);
        }

        if (!$this->repo_mazo->validarMazo($id_usuario, $id_mazo)) {
            return $this->withJson($response, ['error' => 'este mazo no pertence al usuario logueado'], 401);
        }
        $sucess = $this->repo_mazo->actualizarMazo($id_mazo, $nombre_mazo);

        if ($sucess) {
            return $this->withJson($response, ['mensaje' => 'mazo actualizado correctamente']);
        }
        return $this->withJson($response, ['mensaje' => 'no se pudo actualizar el mazo'], 400);

    }

    public function buscarCartasFiltro(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $atributoRaw = $params['atributo'] ?? null;
        $nombre = $params['nombre'] ?? '';

        if ($atributoRaw !== null && !ctype_digit($atributoRaw)) {
            return $this->withJson($response, [
                'error' => 'El parámetro atributo debe ser un número entero.'
            ], 400);
        }


        $atributo = $atributoRaw !== null ? (int) $atributoRaw : null;

        $cartas = $this->repo_cartas->buscarCartasPorAtributoYNombre($atributo, $nombre);

        if (empty($cartas)) {
            return $this->withJson($response, [
                'mensaje' => 'No se encontraron cartas que coincidan con los filtros proporcionados.'
            ], 404);
        }

        $atributo_ids = array_unique(array_column($cartas, 'atributo_id'));
        $nombres_atributo = $this->repo_atributo->atributosID($atributo_ids);

        foreach ($cartas as &$carta) {
            $carta['atributo_nombre'] = $nombres_atributo[$carta['atributo_id']] ?? 'Desconocido';
        }
        return $this->withJson($response, ['status' => 'success',
                'cartas' => $cartas]);
    }

    public function eliminarMazo(Request $request, Response $response, array $args): Response
    {
        $id_mazo = (int) $args['mazo'];
        $id_usuario = $request->getAttribute('id_usuario');
        if (!$this->repo_mazo->validarMazo($id_usuario, $id_mazo)) {
            return $this->withJson($response, ['error' => 'este mazo no pertence al usuario logueado'], 401);
        }
        if ($this->repo_partida->mazoUtilizado($id_mazo)) {
            return $this->withJson($response, ['error' => 'el mazo ya fue utilizado, no se puede borrar'], 409);
        }
        if ($this->repo_mazo->eliminarMazo($id_mazo)) {
            return $this->withJson($response, [
                'status' => 'success',
                'mazo eliminado' => $id_mazo
            ]);
        }
        return $this->withJson($response, ['error' => 'el mazo no se pudo eliminar'], 409);
    }

    public function mostrarMazos(Request $request, Response $response, array $args): Response
    {
        $id_usuario = (int) $args['usuario'];
        $mazos = $this->repo_mazo->buscarMazosPorId($id_usuario) ?? [];

        foreach ($mazos as $key => $value) {
            $datocarta = $this->repo_mazo_carta->buscarIdCartas($mazos[$key]['id']);
            $mazos[$key]['cartas'] = $this->repo_cartas->mostrarCartas($datocarta);
        }
        return $this->withJson($response, [
            'status' => 'success',
            'Listado de mazos' => $mazos
        ]);
    }

    private function withJson(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }
}

<?php

namespace App\Controllers;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repositories\UsuarioRepository;
use App\Repositories\PartidaRepository;
use App\Repositories\MazoCartaRepository;
use App\Repositories\JugadaRepository;
class AuthController
{
    private $secretKey;

    public function __construct(private UsuarioRepository $repo, private PartidaRepository $repo_partida, private MazoCartaRepository $repo_mazo_carta, private JugadaRepository $repo_jugada)
    {
    }

    public function register(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $nombre = $data['nombre'] ?? null;
        $usuario = $data['usuario'] ?? null;

        if (empty($nombre) || empty($usuario)) {
            return $this->withJson($response, ['error' => 'Todos los campos son obligatorios'], 400);
        }

        if (!preg_match('/^[a-zA-Z0-9]{6,20}$/', $data['usuario'])) {
            return $this->withJson($response, ['error' => 'El nombre de usuario debe tener entre 6 y 20 caracteres y solo contener letras y números'], 400);

        }

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
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        $data = $request->getParsedBody();
        $usuario = $this->repo->buscarPorUsuario($data['usuario'] ?? '');

        if (!$usuario || !password_verify($data['clave'] ?? '', $usuario['password'])) {
            return $this->withJson($response, ['status' => 'error', 'message' => 'Credenciales inválidas'], 401);
        }

        $exp = time() + 3600;
        $token = bin2hex(random_bytes(64));
        $token = substr($token, 0, 128);

        $this->repo->guardarToken($usuario['id'], $token, $exp);

        return $this->withJson($response, [
            'status' => 'success',
            'token' => $token
        ]);
    }

    public function verificar(Request $request, Response $response): Response
    {
        $id_usuario = $request->getAttribute('id_usuario');

        $usuario = $this->repo->buscarPorId($id_usuario);

        if (!$usuario) {
            return $this->withJson($response, ['status' => 'error', 'message' => 'Usuario no encontrado'], 404);
        }

        return $this->withJson($response, [
            'status' => 'success',
            'usuario' => $usuario
        ]);
    }

    public function logout(Request $request, Response $response): Response
    {
        $id_usuario = $request->getAttribute('id_usuario');

        if (!$id_usuario) {
            return $this->withJson($response, ['status' => 'error', 'message' => 'Usuario no autenticado'], 401);
        }

        $partida_id = $this->repo_partida->tienePartidaEnCurso($id_usuario);

        if ($partida_id) {
            $mazo_id = $this->repo_partida->obtenerIDMazo($partida_id);

            $this->repo_mazo_carta->actualizarCartas($mazo_id, 'en_mazo');
            $this->repo_mazo_carta->actualizarCartas(1, 'en_mazo');

            $this->repo_partida->finalizarPartida($partida_id, "perdio");

            $this->repo_jugada->eliminarJugadasDePartida($partida_id);
        }

        $this->repo->borrarToken($id_usuario);

        return $this->withJson($response, [
            'status' => 'success',
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    private function withJson(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }
}

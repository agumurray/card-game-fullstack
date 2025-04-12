<?php

namespace App\Middleware;

use App\Repositories\UsuarioRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Routing\RouteContext;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private UsuarioRepository $usuarioRepo)
    {
    }

    public function process(Request $request, Handler $handler): Response
    {
        // Intentar obtener ID de la ruta
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $routeArgs = $route?->getArguments() ?? [];
        $id_usuario = $routeArgs['usuario'] ?? null;

        // Obtener token e ID del body si no vino por la ruta
        $data = $request->getParsedBody();
        $token = $data['token'] ?? null;
        $id_usuario ??= $data['usuario'] ?? null; // Usa el del body si no vino en la ruta

        // Si el ID de usuario no llegó por la ruta ni por el body, buscarlo por token
        if (empty($id_usuario) && !empty($token)) {
            $id_usuario = $this->usuarioRepo->buscarIDPorToken($token);
        }

        // Si el ID o el token no son válidos, devolver error
        if (empty($id_usuario) || empty($token) || !$this->usuarioRepo->tokenValido((int) $id_usuario, $token)) {
            return $this->withJson([
                'status' => 'error',
                'message' => 'Token inválido o expirado'
            ], 401);
        }

        return $handler->handle($request);
    }

    private function withJson(array $data, int $status): Response
    {
        $response = new \Slim\Psr7\Response($status);
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }
}

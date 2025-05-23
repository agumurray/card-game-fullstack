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
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $routeArgs = $route?->getArguments() ?? [];
        $id_usuario = $routeArgs['usuario'] ?? null;

        $method = $request->getMethod();
        $pattern = $route?->getPattern();


        if (
            $method === 'GET' &&
            $pattern === '/usuarios/{usuario}/partidas/{partida}/cartas' &&
            (int) $id_usuario === 1
        ) {
            return $handler->handle($request->withAttribute('id_usuario', 1));
        }

        if (empty($id_usuario)) {
            $data = $request->getParsedBody();
            $id_usuario = $data['usuario'] ?? null;
        }

        if (empty($id_usuario)) {
            $authHeader = $request->getHeaderLine('Authorization');
            $token = null;

            if (!empty($authHeader) && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                $token = $matches[1];
            }

            // Buscar ID usando el token
            if (!empty($token)) {
                $id_usuario = $this->usuarioRepo->buscarIDPorToken($token);
            }
        }

        if (empty($id_usuario)) {
            return $this->withJson([
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ], 400);
        }

        $authHeader = $request->getHeaderLine('Authorization');
        $token = null;
        if (!empty($authHeader) && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
        }

        if (empty($token) || !$this->usuarioRepo->tokenValido((int) $id_usuario, $token)) {
            return $this->withJson([
                'status' => 'error',
                'message' => 'Token inválido o expirado'
            ], 401);
        }

        $request = $request->withAttribute('id_usuario', $id_usuario);
        return $handler->handle($request);
    }

    private function withJson(array $data, int $status): Response
    {
        $response = new \Slim\Psr7\Response($status);
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }
}

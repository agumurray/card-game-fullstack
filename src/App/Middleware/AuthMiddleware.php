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
        $id_usuario ??= $data['usuario'] ?? null;

        $authHeader = $request->getHeaderLine('Authorization');
        $token=null;

        if (!empty($authHeader) && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
        }

        // Si no se recibió el ID pero sí el token, buscar el ID a partir del token
        if (empty($id_usuario) && !empty($token)) {
            $id_usuario = $this->usuarioRepo->buscarIDPorToken($token);
        }

        // Si no se encuentra el ID, devolver error
        if (empty($id_usuario)) {
            return $this->withJson([
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ], 400);
        }
        
        //Si se encuentra el ID pero el usuario no esta logueado, devolver error
        if (empty($token) || !$this->usuarioRepo->tokenValido((int) $id_usuario, $token)) {
            return $this->withJson([
                'status' => 'error',
                'message' => 'Token inválido o expirado'
            ], 401);
        }
        
        // Pasar id_usuario como atributo 
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

<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as SlimResponse;

class ClaveMiddleware
{
    public function __invoke(Request $request, Handler $handler): Response
    {
        $data = $request->getParsedBody();
        $clave = $data['clave'] ?? null;

        if (empty($clave)) {
            return $this->withJson(['error' => 'La contraseña es obligatoria'], 400);
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $clave)) {
            return $this->withJson(['error' => 'La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, una minúscula, un número y un carácter especial'], 400);
        }

        return $handler->handle($request);
    }

    private function withJson(array $data, int $status): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode($data));
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }
}

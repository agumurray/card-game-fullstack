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

        // Validación de la contraseña
        if (!empty($clave) && (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $clave))) {
            return $this->withJson(['error' => 'La contraseña debe tener al menos 8 caracteres, mayúscula, minúscula, al menos un número y un carácter especial'], 400);
        }

        // Si la contraseña es válida, pasa al siguiente middleware o controlador
        return $handler->handle($request);
    }

    // Método para devolver la respuesta JSON con un error
    private function withJson(array $data, int $status): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode($data));
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }
}

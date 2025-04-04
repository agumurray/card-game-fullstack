<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/models/DB.php';


$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->add( function ($request, $handler) {
    $response = $handler->handle($request);

    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, PUT, PATCH, DELETE')
        ->withHeader('Content-Type', 'application/json')
    ;
});

//JWT secret key
$secretKey = getenv('JWT_SECRET');

//login
$app->post('/login', function (Request $request, Response $response) use ($secretKey) {
    try{
        $db = DB::getConnection();

        $data = $request->getParsedBody();
        $nombre = $data['nombre'] ?? '';
        $usuario = $data['usuario'] ?? '';
        $clave = $data['clave'] ?? '';
    
        if (empty($nombre) || empty($usuario) || empty($clave)) {
            $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Nombre, usuario y clave requeridos']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    
        $stmt = $db->prepare("SELECT id, nombre, usuario, password FROM usuario WHERE usuario = :usuario");
        $stmt->execute(['usuario' => $usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user || !password_verify($clave, $user['password'])) {
            $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Credenciales inválidas']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
    
        $exp = time() + 3600;
        $payload = [
            'sub' => $user['id'],
            'name' => $user['nombre'],
            'iat' => time(),
            'exp' => $exp
        ];
        $token = JWT::encode($payload, $secretKey, 'HS256');
    
        $token = substr($token, 0, 128);
    
        $stmt = $db->prepare("UPDATE usuario SET token = :token, vencimiento_token = FROM_UNIXTIME(:exp) WHERE id = :id");
        $stmt->execute(['token' => $token, 'exp' => $exp, 'id' => $user['id']]);
    
        $response->getBody()->write(json_encode([
            'status' => 'success',
            'token' => $token,
        ]));
    
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    } 
    catch (PDOE) {
        $response = $response->withStatus(404);
        $response->getBody()->write(json_encode(['error' => 'User not found or no changes made']));
    }
});


$app->put('/usuario/{usuario}', function (Request $request, Response $response, array $args) {
    $db = DB::getConnection();

    $id_usuario = $args['usuario'];
    $data = json_decode($request->getBody()->getContents(), true);


    $nombre = $data['nombre'];
    $contrasenia = $data['contrasenia'];


    $stmt = $db->prepare("UPDATE usuario SET nombre = :nombre, password = :contrasenia WHERE id = :usuario");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':contrasenia', $contrasenia);
    $stmt->bindParam(':usuario', $id_usuario);

    if ($stmt->execute()) {
        $response->getBody()->write(json_encode(['mensaje' => 'Usuario actualizado correctamente']));
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        $response->getBody()->write(json_encode(['error' => 'No se pudo actualizar el usuario']));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }

});


$app->run();

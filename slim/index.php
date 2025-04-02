<?php
require __DIR__ . '/vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
//use Dotenv\Dotenv;



//$dotenv = Dotenv::createImmutable(__DIR__ );
//$dotenv->load();


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


//DB CONNECTION
$dsn = 'mysql:host=db;dbname=' . getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$pdo = new PDO($dsn, $username, $password);


//JWT secret key
$secretKey = getenv('JWT_SECRET');

//login
$app->post('/login', function (Request $request, Response $response) use ($pdo, $secretKey) {
    $data = $request->getParsedBody();
    $nombre = $data['nombre'] ?? '';
    $usuario = $data['usuario'] ?? '';
    $clave = $data['clave'] ?? '';

    if (empty($nombre) || empty($usuario) || empty($clave)) {
        $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Nombre, usuario y clave requeridos']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    $stmt = $pdo->prepare("SELECT id, nombre, usuario, password FROM usuario WHERE usuario = :usuario");
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

    $stmt = $pdo->prepare("UPDATE usuario SET token = :token, vencimiento_token = FROM_UNIXTIME(:exp) WHERE id = :id");
    $stmt->execute(['token' => $token, 'exp' => $exp, 'id' => $user['id']]);

    $response->getBody()->write(json_encode([
        'status' => 'success',
        'token' => $token,
    ]));

    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->run();

<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

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
//!no hacer commit de las credenciales reales de la DB
$dsn = 'mysql:host=db;dbname=seminariophp';
$username='seminariophp';
$password= 'seminariophp';
$pdo = new PDO($dsn, $username, $password);


// Endpoint para probar la conexión con la base de datos
$app->get('/test_db', function (Request $request, Response $response) use ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM carta");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode(['status' => 'success', 'data' => $result]));
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(['status' => 'error', 'message' => $e->getMessage()]));
    }

    return $response;
});



$app->run();

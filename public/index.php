<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\UsuarioController;
use App\Controllers\MazoController;
use App\Controllers\PartidaController;
use App\Middleware\CorsMiddleware;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . "/vendor/autoload.php";

$builder = new ContainerBuilder();

$container = $builder->addDefinitions(APP_ROOT . '/config/definitions.php')->build();

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');

// Middleware CORS
$app->add(new CorsMiddleware());

// Ruta simple para test
$app->get('/', function (
    ServerRequestInterface $request,
    ResponseInterface $response
): ResponseInterface {
    $response->getBody()->write(json_encode(['message' => 'Hello, world!']));
    return $response->withHeader('Content-Type', 'application/json');
});

// Autenticación
$app->post('/register', [AuthController::class, 'register']);
$app->post('/login', [AuthController::class, 'login']);

// Usuario
$app->put('/usuario/{usuario}', [UsuarioController::class, 'actualizar']);

//Mazo
$app->post('/mazos', [MazoController::class, 'agregar']);

//Partida
$app->post('/partida', [PartidaController::class, 'crearPartida']);

$app->run();

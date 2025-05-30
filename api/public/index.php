<?php

declare(strict_types=1);

date_default_timezone_set('America/Argentina/Buenos_Aires');

use App\Controllers\AuthController;
use App\Controllers\JuegoController;
use App\Controllers\UsuarioController;
use App\Controllers\MazoController;

use App\Middleware\AuthMiddleware;
use App\Middleware\ClaveMiddleware;
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

$app->add(new CorsMiddleware());

$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
    $response->getBody()->write(json_encode(['message' => 'Hello, world!']));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/registro', [AuthController::class, 'register'])
    ->add(ClaveMiddleware::class);
$app->post('/login', [AuthController::class, 'login']);

$app->put('/usuarios/{usuario}', [UsuarioController::class, 'actualizar'])
    ->add(AuthMiddleware::class)
    ->add(ClaveMiddleware::class);

$app->get('/usuarios/{usuario}', [UsuarioController::class, 'obtener'])
    ->add(AuthMiddleware::class);

$app->post('/mazos', [MazoController::class, 'agregar'])
    ->add(AuthMiddleware::class);

$app->get('/usuarios/{usuario}/mazos', [MazoController::class, 'mostrarMazos'])
    ->add(AuthMiddleware::class);

$app->get('/cartas', [MazoController::class, 'buscarCartasFiltro']);

$app->put('/mazos/{mazo}', [MazoController::class, 'actualizarMazo'])
    ->add(AuthMiddleware::class);

$app->delete('/mazos/{mazo}', [MazoController::class, 'eliminarMazo'])
    ->add(AuthMiddleware::class);
    
$app->post('/partidas', [JuegoController::class, 'crearPartida'])
    ->add(AuthMiddleware::class);

$app->post('/jugadas', [JuegoController::class, 'crearJugada'])
    ->add(AuthMiddleware::class);

$app->get('/usuarios/{usuario}/partidas/{partida}/cartas', [JuegoController::class, 'cartasEnJuego'])
    ->add(AuthMiddleware::class);

$app->get('/estadisticas', [JuegoController::class, 'estadisticas']);

$app->get('/yo', [AuthController::class, 'verificar'])
    ->add(AuthMiddleware::class);

$app->post('/logout', [AuthController::class, 'logout'])
    ->add(AuthMiddleware::class);

$app->run();
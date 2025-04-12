<?php

use App\Database;
use App\Repositories\UsuarioRepository;
use App\Middleware\AuthMiddleware;
use App\Middleware\ClaveMiddleware;

return [
    // Instancia de la base de datos
    Database::class => function() {
        return new Database(
            host: getenv('DB_HOST'),
            name: getenv('DB_NAME'),
            user: getenv('DB_USER'),
            password: getenv('DB_PASSWORD')
        );
    },

    // Repositorios
    UsuarioRepository::class => fn($c) => new UsuarioRepository($c->get(Database::class)),

    // Middlewares
    AuthMiddleware::class => fn($c) => new AuthMiddleware($c->get(UsuarioRepository::class)),
    ClaveMiddleware::class => fn($c) => new ClaveMiddleware(),
    UsuarioRepository::class => function ($container) {
        return new UsuarioRepository($container->get(Database::class));
    },
];

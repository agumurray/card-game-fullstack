<?php

use App\Database;

return [
    Database::class => function() {
        return new Database(
            host: getenv('DB_HOST'),
            name: getenv('DB_NAME'),
            user: getenv('DB_USER'),
            password: getenv('DB_PASSWORD')
        );
    }
];
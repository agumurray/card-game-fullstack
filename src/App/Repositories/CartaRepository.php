<?php

namespace App\Repositories;

use App\Database;
use PDO;

class CartaRepository
{
    public function __construct(private Database $database)
    {
    }

    public function validarCartas(array $cartas): bool
    {
        $placeholders = implode(',', array_fill(0, count($cartas), '?'));

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM carta WHERE id IN ($placeholders)");
        $stmt->execute($cartas);
        $cantidad = $stmt->fetchColumn();

        if ($cantidad != count($cartas)) {
            return false;
        }
        return true;
    }
}
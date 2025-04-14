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

    public function obtenerFuerza(int $id_carta): int
    {
        $pdo = $this->database->getConnection();
    
        $stmt = $pdo->prepare("SELECT ataque FROM carta WHERE id = :id_carta");
        $stmt->execute([':id_carta' => $id_carta]);
    
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $resultado['ataque'] ?? 0;
    }

    public function obtenerAtributo(int $id_carta): int
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT atributo_id FROM carta WHERE id = :id_carta");
        $stmt->execute([':id_carta' => $id_carta]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['atributo_id'] ?? 0;
    }

    
}
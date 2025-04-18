<?php

namespace App\Repositories;

use App\Database;
use PDO;

class AtributoRepository
{
    public function __construct(private Database $database)
    {
    }

    public function obtenerAtributosPorIds(array $atributoIds): array
    {
        if (empty($atributoIds)) {
            return [];
        }
    
        $placeholders = implode(',', array_fill(0, count($atributoIds), '?'));
        $query = "SELECT id, nombre FROM atributo WHERE id IN ($placeholders)";
    
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($query);
        $stmt->execute($atributoIds);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
















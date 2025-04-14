<?php

namespace App\Repositories;

use App\Database;
use PDO;

class GanaARepository
{
    public function __construct(private Database $database)
    {
    }

    public function ventaja(int $id1, int $id2): ?int
    {
        $pdo = $this->database->getConnection();
    
        $stmt = $pdo->prepare("
            SELECT atributo_id 
            FROM gana_a 
            WHERE (atributo_id = :id1 AND atributo_id2 = :id2)
               OR (atributo_id = :id2 AND atributo_id2 = :id1)
            LIMIT 1
        ");
    
        $stmt->execute([':id1' => $id1, ':id2' => $id2]);
    
        $ganador = $stmt->fetchColumn();
    
        return $ganador !== false ? (int) $ganador : null;
    }
    
}
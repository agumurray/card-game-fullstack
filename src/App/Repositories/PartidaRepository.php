<?php

namespace App\Repositories;

use App\Database;
use PDO;

class PartidaRepository
{
    public function __construct(private Database $database)
    {
    }

    public function crearPartida(int $id_usuario,int $id_mazo): int|false
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("INSERT INTO partida(usuario_id,mazo_id,estado) VALUES (:id_usuario,:id_mazo,'en_curso')");
        if( $stmt->execute([
            ':id_usuario'=>$id_usuario,
            ':id_mazo'=> $id_mazo
        ])){
            return (int) $pdo->lastInsertId();
        }
        return false;
    }
}
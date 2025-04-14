<?php

namespace App\Repositories;

use App\Database;
use PDO;

class PartidaRepository
{
    public function __construct(private Database $database)
    {
    }

    public function crearPartida(int $id_usuario,int $id_mazo): bool
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("INSERT INTO partida(usuario_id,mazo_id,estado) VALUES (:id_usuario,:id_mazo,'en_curso')");
        return $stmt->execute([
            ':id_usuario'=>$id_usuario,
            ':id_mazo'=> $id_mazo
        ]);
    }

    public function obtenerIDMazo(int $id_partida): int
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare("SELECT mazo_id FROM partida WHERE id = :id_partida");
        $stmt->execute([':id_partida' => $id_partida]);

        return $stmt->fetchColumn();
    }

    public function finalizarPartida(int $id_partida, string $resultado): void
    {
        $pdo = $this->database->getConnection();

        $update = $pdo->prepare("UPDATE partida SET el_usuario = :resultado, estado = 'finalizada' WHERE id = :id_partida");
        $update->execute([
            'resultado' => $resultado,
            'id_partida' => $id_partida
        ]);
    }
}